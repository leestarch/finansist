<?php

namespace App\Console\Commands\Operation;

use App\Models\Operation;
use App\Models\OperationRule;
use Illuminate\Console\Command;

class OperationValidateCommand extends Command
{
    protected $signature = 'operation.validate';
    public function handle(): void
    {
        $operationsQuery = Operation::query();
        $rules = OperationRule::all();
        foreach ($operationsQuery->cursor() as $operation) {
//            dump($operation);
            $operationRule= OperationRule::validateOperation($operation, $rules);
            if($operationRule)
                $res = $operation->categories()->sync([
                    $operationRule->category_id => ['rule_id' => $operationRule->id]
                ]);
        }
    }
}
