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
        foreach ($operationsQuery->cursor() as $operation) {
//            dump($operation);
            $operationRule= OperationRule::validateOperation($operation);
            if($operationRule)
                $res = $operation->categories()->sync([
                    $operationRule->category_id => ['rule_id' => $operationRule->id]
                ]);
        }
    }
}
