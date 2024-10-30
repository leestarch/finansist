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
