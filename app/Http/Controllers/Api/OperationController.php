<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operation\OperationCreateRequest;
use App\Http\Requests\Operation\OperationSeedFromAPIRequest;
use App\Http\Resources\OperationResource;
use App\Models\Category;
use App\Models\Operation;
use App\Models\Type;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($categoryId = $request->input('parentCategoryId')) {
            $category = Category::query()->find($categoryId);
            $categoryIds = $category->geyNestedChildren();
            $categoryIds[] = (int)$categoryId;
            $request->merge(['categoryIds' => $categoryIds]);
        }

        $operationsQuery = Operation::query()
            ->filter($request->all())
            ->with('categories', 'types');

        $operations = $operationsQuery->paginate($request->input('paginate', 50))
            ->withQueryString();

        return OperationResource::collection($operations);
    }

    public function summary(Request $request): JsonResponse
    {
        $dateFrom = $request->input('dateFrom') ?: Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->input('dateTo') ?: Carbon::now()->endOfMonth()->toDateString();

        $categoryFilter = $request->input('category');

        $query = Operation::query()
            ->with('categories')
            ->selectRaw('categories.name as category, DATE(date_at) as date, SUM(sber_amountRub) as daily_total')
            ->join('categories_operations', 'operations.id', '=', 'categories_operations.operation_id')
            ->join('categories', 'categories_operations.category_id', '=', 'categories.id')
            ->whereDate('date_at', '>=', $dateFrom)
            ->whereDate('date_at', '<=', $dateTo);

        // Filter by category if provided
        if ($categoryFilter) {
            $query->where('categories.name', $categoryFilter['name']);
        }

        $query->groupBy('categories.name', 'date')
            ->orderBy('categories.name')
            ->orderBy('date_at');

        $dailySummaries = $query->get();

        $formattedData = [];
        foreach ($dailySummaries as $record) {
            $category = $record->category;
            $date = $record->date_at;
            $sber_amountRub = $record->daily_total;

            if (!isset($formattedData[$category])) {
                $formattedData[$category] = ['category' => $category, 'total' => 0];
            }
            $formattedData[$category][$date] = $sber_amountRub;
            $formattedData[$category]['total'] += $sber_amountRub;
        }

        $result = array_values($formattedData);

        return response()->json([
            'data' => $result,
            'types' => Type::query()->select(['id', 'name'])->get(),
            'categories' => Category::query()->select(['id', 'name'])->get(),
        ]);
    }

    public function store(OperationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $data = $request->validated();
        $request['is_manual'] = true;
        $categories = $data['categories'] ?? null;
        unset($data['categories']);

        $operation = Operation::query()->create($data);
        if ($categories) {

            try {
                $operation->refresh();
                $categoriesToHandle = $this->mapCategories($categories);
                $operation->handleCategories($categoriesToHandle);
            } catch (\Exception $exception) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ]);
            }
        }
        DB::commit();
        return response()->json([
            'success' => true,
        ]);
    }

    public function show(int $id, Request $request): OperationResource
    {
        $operationQuery = Operation::query();
        if ($request->get('include')) {
            $operationQuery->with(explode(',', $request->get('include')));
        }

        return OperationResource::make($operationQuery->findOrFail($id));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $operation = Operation::query()->findOrFail($id);

        DB::beginTransaction();
        $operation->update($request->except('categories'));
        $operation->refresh();

        if ($categories = $request->get('categories')) {
            $categoriesToHandle = $this->mapCategories($categories);

            try {
                $operation->handleCategories($categoriesToHandle);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
            unset($request['categories']);
        }
        $request['is_manual'] = true;
        DB::commit();

        return response()->json([
            'success' => true,
        ]);
    }

    private function mapCategories(array $categories): array
    {
        $mappedCategories = [];
        foreach ($categories as $category) {
            $mappedCategories[$category['id']] = (int)$category['sber_amountRub'];
        }
        return $mappedCategories;
    }

    public function seed(OperationSeedFromAPIRequest $request): JsonResponse
    {
        $validate = $request->validated();
        $data = $validate['data'];
        foreach ($data as $operation) {
            Operation::query()->firstOrCreate($operation);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public function getOperations()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://api.lookin.team/api/finance/fin-lookin/operations', [
                'start_at' => '2024-12-01'
            ]);

// Обработка ответа
            $body = $response->getBody();
            $transactions = collect(json_decode($body)->data); // Декодируем JSON в ассоциативный масси
            foreach ($transactions as $transaction) {
                Operation::updateOrCreate([
                    'sber_operationId' => $transaction->sber_operationId,
                ], [
                    'pizzeria_id' => $transaction->pizzeria->id,
                    'date_at' => $transaction->date_at,
                    'sber_amountRub' => $transaction->sber_amountRub,
                    'sber_direction' => $transaction->sber_direction,
                    'sber_paymentPurpose' => $transaction->sber_paymentPurpose,
                    'sber_operationId' => $transaction->sber_operationId,
                    'payer_contractor_id' => $transaction->payer_contractor_id,
                    'payee_contractor_id' => $transaction->payee_contractor_id,
                    'is_manual' => $transaction->is_manual,
                    'created_at' => $transaction->created_at,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in getOperations():', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}
