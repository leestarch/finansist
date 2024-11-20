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
        $operationsQuery = Operation::query()->where('sber_direction', Operation::DEBIT);
        foreach ($operationsQuery->cursor() as $operation) {
            $updatedOperationsCount = OperationRule::validateOperation($operation);
            dump('Операция проверена, изменено категорий у операций: ' . $updatedOperationsCount);
        }
    }
}
