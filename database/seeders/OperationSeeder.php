<?php

namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFilePath = storage_path('app/private/categories.sql');
        $sqlContent = file_get_contents($sqlFilePath);
        DB::unprepared($sqlContent);

        $sqlFilePath = storage_path('app/private/operations.sql');
        $sqlContent = file_get_contents($sqlFilePath);
        DB::unprepared($sqlContent);

//        $sqlFilePath = storage_path('app/private/dodo_sber_transaction_budget_category.sql');
//        $sqlContent = file_get_contents($sqlFilePath);
//        DB::unprepared($sqlContent);
    }
}
