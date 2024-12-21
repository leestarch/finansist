<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operation\OperationRuleStore;
use App\Http\Resources\OperationRuleResource;
use App\Models\Contractor;
use App\Models\Operation;
use App\Models\OperationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class OperationRuleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $paginate = $request->get('paginate', 50);
        $rules = OperationRule::query();
        if ($load = $request->get('load')) {
            $rules->with(explode(',', $load[0]));
        }

        $rules->filter($request->all());


        return OperationRuleResource::collection($rules->paginate($paginate));
    }

    public function show(int $id, Request $request): OperationRuleResource
    {
        $ruleQuery = OperationRule::query();
        if ($include = $request->get('include')) {
            $include = explode(',', $include);
            $ruleQuery->with($include);
        }
        return OperationRuleResource::make($ruleQuery->findOrFail($id));
    }

    public function store(OperationRuleStore $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['contractor_ids'])) {
            OperationRule::query()->firstOrCreate([
                'category_id' => $data['category_id'],
                'contractor_id' => null,
                'purpose_expression' => $this->handleExpression($data['purpose_expression'] ?? null),
            ]);
        } else {
            foreach ($data['contractor_ids'] as $contractorId) {
                OperationRule::query()->firstOrCreate([
                    'category_id' => $data['category_id'],
                    'contractor_id' => $contractorId,
                    'purpose_expression' => $this->handleExpression($data['purpose_expression'] ?? null),
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $operationType = $request->get('operation_type');
        if (is_array($operationType)) {
            $operationType = $operationType['value'];
        }

        $rule = OperationRule::query()->findOrFail($id);
        $rule->update([
            'purpose_expression' => $this->handleExpression($request->purpose_expression),
            'category_id' => $request->category_id,
            'operation_type' => $operationType,
        ]);

        return response()->json([
            'success' => true,
            'rule' => $rule,
            'request' => $request
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

    private function handleExpression(?string $expression): ?string
    {
        if ($expression) {
            return Str::start(Str::finish($expression, '/'), '/');
        }
        return null;
    }

    public function getOperationsByRule(Request $request)
    {
        $rule = (object)$request->input('rule');
        if (!property_exists($rule, 'contractor_id')) $rule->contractor_id = null;
        if (!property_exists($rule, 'purpose_expression')) $rule->purpose_expression = null;
        if (!property_exists($rule, 'operation_type')) $rule->operation_type = null;
        $operations = OperationRule::getOperationsByRule($rule);
        return $operations;
    }

    public function validateOperationsByRule(Request $request)
    {
        $rule = (object)$request->input('rule');
        if (!property_exists($rule, 'contractor_id')) $rule->contractor_id = null;
        if (!property_exists($rule, 'purpose_expression')) $rule->purpose_expression = null;
        if (!property_exists($rule, 'operation_type')) $rule->operation_type = null;
        $operations = OperationRule::getOperationsByRule($rule);
        foreach ($operations as $operation) {
            $res = $operation->categories()->sync([
                $rule->category_id => ['rule_id' => $rule->id]
            ]);
        }
    }
}
