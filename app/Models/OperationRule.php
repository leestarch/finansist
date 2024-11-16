<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OperationRule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function validateOperations(Collection $operations): int
    {
        $updatedOperations = 0;
        foreach ($operations as $operation){
            $payeeContractorId = $operation->payee_contractor_id;
            $rules = OperationRule::query()->where('contractor_id', $payeeContractorId)->get()
                ->sortByDesc(function ($rule) {
                    return !empty($rule->purpose_expression);
                });

            foreach ($rules as $rule){
                if($expression = $rule->purpose_expression){
                    $isValid = self::validateExpression($operation, $expression);
                    if($isValid && $rule->contractor_id === $payeeContractorId){
                        $operation->categories()->sync($rule->category_id);
                        $updatedOperations++;
                        break;
                    }
                }else{
                    if($rule->contractor_id === $payeeContractorId){
                        $operation->categories()->sync($rule->category_id);
                        $updatedOperations++;
                        break;
                    }
                }
            }
        }
        return $updatedOperations;
    }

    private static function validateExpression(Operation $operation, string $expression): bool
    {
        return preg_match($expression, $operation->sber_paymentPurpose) === 1;
    }
}
