<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AppRefreshCommand extends Command
{
    protected $signature = 'app.refresh {--w-operations}';
    public function handle()
    {

        if($this->option('w-operations')){
            DB::table('operations')->truncate();
            $this->call('db:seed', [
                '--class' => 'OperationSeeder'
            ]);
        }

        DB::table('categories')->truncate();
        DB::table('categories_operations')->truncate();
        DB::table('contractor_pizzeria')->truncate();
        DB::table('contractors')->truncate();
        DB::table('operation_rules')->truncate();
        DB::table('operations_types')->truncate();
        DB::table('pizzerias')->truncate();
        DB::table('types')->truncate();

        $this->call('db:seed');
        $this->call('seed.categories', [
            '--from-sql' => true   // --from-json
        ]);

        $this->call('operation.categories.manage.table', [
            '--validate' => true    // --restore заполнение из dodo_sber_transaction_budget_category
        ]);
    }
}

