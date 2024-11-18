<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operation\OperationRuleStore;
use App\Http\Resources\OperationRuleResource;
use App\Models\OperationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OperationRuleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $rules = OperationRule::query();
        $paginate = $request->get('paginate', 50);
        if($request->category_id){
            $rules->where('category_id', $request->category_id);
        }

        if ($request->contractor_id){
            $rules->where('contractor_id', $request->contractor_id);
        }

        return OperationRuleResource::collection($rules->paginate($paginate));
    }

    public function store(OperationRuleStore $request): JsonResponse
    {
        $data = $request->validated();
        foreach ($data['contractor_ids'] as $contractorId){
            OperationRule::query()->firstOrCreate([
                'category_id' => $data['category_id'],
                'contractor_id' => $contractorId,
                'purpose_expression' => $data['purpose_expression'] ?? null,
                'name' => $data['name'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
