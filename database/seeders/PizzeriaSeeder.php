<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzeriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFilePath = storage_path('app/private/pizzerias.sql');
        $sqlContent = file_get_contents($sqlFilePath);
        DB::unprepared($sqlContent);
    }
}
