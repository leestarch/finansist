<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operation\OperationCreateRequest;
use App\Http\Resources\OperationResource;
use App\Models\Category;
use App\Models\Operation;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

class OperationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        if($categoryId = $request->input('parentCategoryId')) {
            $category = Category::query()->find($categoryId);
            $categoryIds = $category->geyNestedChildren();
            $categoryIds[] = (int) $categoryId;
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

    public function create(): JsonResponse
    {
        $categories = Category::query()->select(['id', 'name'])->get();
        $types = Type::query()->select(['id', 'name'])->get();
        return response()->json([
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    public function store(OperationCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $operation = Operation::query()->create([
            'sber_amountRub' => $data['sber_amountRub'],
            'description' => $data['description'],
            'is_completed' => $data['is_completed'],
            'date_at' => $data['date_at'],
        ]);
        $operation->categories()->attach($data['category']['id']);
        $operation->types()->attach($data['type']['id']);
        return response()->json([
            'success' => true,
        ]);
    }

    public function show(int $id, Request $request): OperationResource
    {
        $operationQuery = Operation::query();
        if($request->get('include')) {
            $operationQuery->with(explode(',', $request->get('include')));
        }

        return OperationResource::make($operationQuery->findOrFail($id));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        dd($request->all());
        $operation = Operation::query()->findOrFail($id);
        $operation->update($request->all());
        return response()->json([
            'success' => true,
        ]);
    }
}
