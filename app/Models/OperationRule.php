<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OperationRule extends Model
{
    use HasFactory;

    const DEBIT = 'DEBIT';
    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function scopeFilter(Builder $query, array $filter): Builder
    {
        if ($categoryId = $filter['category_id'] ?? null) {
            $query->where('category_id', $categoryId);
        }

        if ($contractorId = $filter['contractor_id'] ?? null) {
            $query->where('contractor_id', $contractorId);

            $includeCommons = $filter['include_commons'] ?? true;
            if ($includeCommons !== 'false') {
                $query->orWhereNull('contractor_id');
            }
        }

        if ($contractorIds = $filter['contractor_ids'] ?? null) {
            $query->whereIn('contractor_id', $contractorIds);

            $includeCommons = $filter['include_commons'] ?? true;
            if ($includeCommons !== 'false') {
                $query->orWhereNull('contractor_id');
            }
        }

        if ($purpose_expression = $filter['purpose_expression'] ?? null) {
            $query->where('purpose_expression', 'LIKE', "%$purpose_expression%");
        }

        if ($operation_type = $filter['operation_type'] ?? null) {
            $query->where('operation_type', $operation_type);
        }

        return $query;
    }

    public static function validateOperations(Collection $operations): int
    {
        $updatedOperations = 0;
        foreach ($operations as $operation) {
            self::validateOperation($operation) && $updatedOperations++;
        }
        return $updatedOperations;
    }

    /**
     * Проверяет операцию по правилам, возвращает правило по которому проходит операция или null, если ничего не нашлось
     * @param $operation
     * @return OperationRule|null
     */
    public static function validateOperation($operation, Collection $rules): OperationRule|null
    {
        //Если операция изменена вручную - не меняем категории
        if ($operation->is_manual) return null;

        $payeeContractorId = $operation->payee_contractor_id;

        $filteredRules = $rules->filter(function ($query) use ($payeeContractorId) {
            return $query->where('contractor_id', $payeeContractorId)
                ->orWhereNull('contractor_id');
        })
            ->sortByDesc(function ($rule) {
                return !empty($rule->purpose_expression);
            })->sortByDesc(function ($rule) {
                return !is_null($rule->contractor_id);
            });

        foreach ($filteredRules as $rule) {
            if ($validatedRule = self::validateOperationByRule($rule, $operation)) return $validatedRule;
        }
        return null;
    }

    private static function validateExpression(Operation $operation, string $expression): bool
    {
        try {
            return preg_match($expression, $operation->sber_paymentPurpose) === 1;
        } catch (\Exception $e) {
            $expression = Str::start($expression, '/');
            $expression = Str::finish($expression, '/');
            return preg_match($expression, $operation->sber_paymentPurpose) === 1;
        }

    }

    public static function validateOperationByRule($rule, $operation)
    {
        if ($expression = $rule->purpose_expression) {
            $isValid = self::validateExpression($operation, $expression);
            if (($isValid && $rule?->contractor_id == $operation->payee_contractor_id) || ($isValid && $rule?->contractor_id === null)) {
                return $rule;
            }
        } else {
            if ($rule?->contractor_id == $operation->payee_contractor_id) {
                return $rule;
            }
        }
        return null;
    }

    public static function getOperationsByRule($rule)
    {
        $verifiedOperations = collect();
        $operations = Operation::with(['payeeContractor', 'categories'])->get();
        foreach ($operations as $operation) {
            $payeeContractorId = $operation->payee_contractor_id;
            if ($expression = $rule->purpose_expression) {
                $isValid = OperationRule::validateExpression($operation, $expression);
                if (($isValid && $rule?->contractor_id == $payeeContractorId) || ($isValid && $rule?->contractor_id === null)) {
                    $verifiedOperations->push($operation);
                }
            } else {
                if ($rule?->contractor_id == $payeeContractorId) {
                    $verifiedOperations->push($operation);
                }
            }
        }
        return $verifiedOperations;
    }

}
