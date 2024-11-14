<?php

namespace App\Console\Commands\Operation;

use App\Models\Operation;
use App\Models\OperationRule;
use Illuminate\Console\Command;

class OperationValidateCommand extends Command
{
    protected $signature = 'operation.validate';
    public function handle()
    {
        $operations = Operation::query()->where('sber_direction', Operation::DEBIT);

        foreach ($operations->cursor() as $operation){
            $payeeContractorId = $operation->payee_contractor_id;

            $rules = OperationRule::query()->where('contractor_id', $payeeContractorId)->get()
                ->sortByDesc(function ($rule) {
                    return !empty($rule->purpose_expression);
                });

            foreach ($rules as $rule){
                if($expression = $rule->purpose_expression){
                    $isValid = $this->validateExpression($operation, $expression);
                    if($isValid && $rule->contractor_id === $payeeContractorId){
                        $operation->categories()->sync($rule->category_id); // TODO sync or attach ?
                        break;
                    }
                }else{
                    if($rule->contractor_id === $payeeContractorId){
                        $operation->categories()->sync($rule->category_id);
                        break;
                    }
                }
            }
        }
    }

    private function validateExpression(Operation $operation, string $expression): bool
    {
        return preg_match($expression, $operation->sber_paymentPurpose) === 1;
    }
}
