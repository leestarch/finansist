<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFilePath = storage_path('app/private/contractors.sql');
        $sqlContent = file_get_contents($sqlFilePath);
        DB::unprepared($sqlContent);
    }
}
