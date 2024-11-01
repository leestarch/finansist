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
use Illuminate\Support\Carbon;

class OperationController extends Controller
{
    public function index(Request $request)
    {
        $operationsQuery = Operation::query()
            ->with('categories', 'types');

        if ($type = $request->type) {
            $operationsQuery->whereHas('types', function ($query) use ($type) {
                $query->where('name', $type['name']);
            });
        }

        if ($category = $request->category) {

            $operationsQuery->whereHas('categories', function ($query) use ($category) {
                $query->where('name', $category['name']);
            });
        }

        if ($dateFrom = $request->dateFrom) {
            $operationsQuery->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo = $request->dateTo) {
            $operationsQuery->whereDate('date', '<=', $dateTo);
        }

        $totalIncome = $operationsQuery->clone()
            ->where('type', Operation::INCOME)
            ->sum('amount');
        $totalExpense = $operationsQuery->clone()
            ->where('type', Operation::EXPENSE)
            ->sum('amount');

        $operations = $operationsQuery->paginate($request->input('paginate', 50))->withQueryString();
        return response()->json([
            'operations' => OperationResource::collection($operations),
            'types' => Type::query()->select(['id', 'name'])->get()->toArray(),
            'categories' => Category::query()->select(['id', 'name'])->get()->toArray(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $dateFrom = $request->input('dateFrom') ?: Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->input('dateTo') ?: Carbon::now()->endOfMonth()->toDateString();

        $categoryFilter = $request->input('category');

        $query = Operation::query()
            ->with('categories')
            ->selectRaw('categories.name as category, DATE(date) as date, SUM(amount) as daily_total')
            ->join('categories_operations', 'operations.id', '=', 'categories_operations.operation_id')
            ->join('categories', 'categories_operations.category_id', '=', 'categories.id')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo);

        // Filter by category if provided
        if ($categoryFilter) {
            $query->where('categories.name', $categoryFilter['name']);
        }

        $query->groupBy('categories.name', 'date')
            ->orderBy('categories.name')
            ->orderBy('date');

        $dailySummaries = $query->get();

        $formattedData = [];
        foreach ($dailySummaries as $record) {
            $category = $record->category;
            $date = $record->date;
            $amount = $record->daily_total;

            if (!isset($formattedData[$category])) {
                $formattedData[$category] = ['category' => $category, 'total' => 0];
            }
            $formattedData[$category][$date] = $amount;
            $formattedData[$category]['total'] += $amount;
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
            'amount' => $data['amount'],
            'description' => $data['description'],
            'is_completed' => $data['is_completed'],
            'date' => $data['date'],
        ]);
        $operation->categories()->attach($data['category']['id']);
        $operation->types()->attach($data['type']['id']);
        return response()->json([
            'success' => true,
        ]);
    }
}
