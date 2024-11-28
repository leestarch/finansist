<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OperationCategoriesTableCommand extends Command
{
    protected $signature = 'operation.categories.manage.table {--restore} {--validate}';
    public function handle()
    {
        DB::table('categories_operations')->truncate();

        if($this->option('restore')){
            $sqlFilePath = storage_path('app/private/dodo_sber_transaction_budget_category.sql');
            $sqlContent = file_get_contents($sqlFilePath);
            DB::unprepared($sqlContent);
        }
        if($this->option('validate')) {
            Artisan::call('operation.validate');
        }
    }
}
