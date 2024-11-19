<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

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
                        $res = $operation->categories()->sync($rule->category_id);
                        $updatedOperations = $updatedOperations + sizeof($res['updated']);
                        break;
                    }
                }else{
                    if($rule->contractor_id === $payeeContractorId){
                        $res = $operation->categories()->sync($rule->category_id);
                        $updatedOperations = $updatedOperations + sizeof($res['updated']);
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
