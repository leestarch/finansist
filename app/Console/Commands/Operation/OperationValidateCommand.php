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
        $operations = Operation::query()->where('sber_direction', Operation::DEBIT)->get();
        $updatedOperationsCount = OperationRule::validateOperations($operations);
        dd('Операции проверены, изменено категорий у операций: ' . $updatedOperationsCount);
    }
}
