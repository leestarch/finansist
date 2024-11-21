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

        if($request->uniqueOperations)
            $rules->groupBy('name')
                ->selectRaw('
                name,
                ANY_VALUE(id) as id, 
                ANY_VALUE(purpose_expression) as purpose_expression, 
                ANY_VALUE(category_id) as category_id, 
                ANY_VALUE(operation_type) as operation_type
            ');

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
        // TODO КАК ОБНОВЛЯТЬ КАТЕГОРИИ?
        $operationType = $request->get('operation_type');
        if(is_array($operationType)){
            $operationType = $operationType['value'];
        }

        $rules = OperationRule::query()->where('name', $request->name)->get();
        $updated = 0;
        foreach ($rules as $rule){
            $updated += $rule->update([
                'name' => $request->name,
                'purpose_expression' => $request->purpose_expression,
                'category_id' => $request->category_id,
                'operation_type' => $operationType,
            ]);
        }

        return response()->json([
            'success' => true,
            'rules_updated' => $updated,
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
