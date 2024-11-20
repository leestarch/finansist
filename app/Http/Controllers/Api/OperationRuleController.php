<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operation\OperationRuleStore;
use App\Http\Resources\OperationRuleResource;
use App\Models\Contractor;
use App\Models\OperationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OperationRuleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paginate = $request->get('paginate', 50);
        $rules = OperationRule::query();
        if($load = $request->get('load')){
            $rules->with($load);
        }

        $rules->filter($request->all());

        return OperationRuleResource::collection($rules->paginate($paginate));
    }

    public function show(int $id, Request $request): OperationRuleResource
    {
        $ruleQuery = OperationRule::query();
        if($include = $request->get('include')) {
            $include = explode(',', $include);
            $ruleQuery->with($include);
        }
        return OperationRuleResource::make($ruleQuery->findOrFail($id));
    }

    public function store(OperationRuleStore $request): JsonResponse
    {
        $data = $request->validated();
        if(empty($data['contractor_ids'])){
            $data['contractor_ids'] = Contractor::query()->pluck('id')->toArray();
        }
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

    public function update(int $id, Request $request): JsonResponse
    {
        $rule = OperationRule::query()->findOrFail($id);
        $operationType = $request->get('operation_type');
        if(is_array($operationType)){
            $operationType = $operationType['value'];
        }
        $rule->update([
            'name' => $request->name,
            'purpose_expression' => $request->purpose_expression,
            'category_id' => $request->category_id,
            'contractor_id' => $request->contractor_id,
            'operation_type' => $operationType,
        ]);
        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $rule = OperationRule::query()->findOrFail($id);
        $rule->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}
