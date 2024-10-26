<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperationResource;
use App\Models\Operation;
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

        $operations = $operationsQuery->paginate($request->input('paginate', 50))->withQueryString();
        return response()->json([
            'operations' => OperationResource::collection($operations),
            'types' => \App\Models\Type::query()->select(['id', 'name'])->get()->toArray(),
            'categories' => \App\Models\Category::query()->select(['id', 'name'])->get()->toArray(),
        ]);
    }
}
