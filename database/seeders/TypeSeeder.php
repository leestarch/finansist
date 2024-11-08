<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (["Outcome", "Assets", "Capital", "Income", "Liabilities",] as $type) {
            Type::query()->create([
                'name' => $type,
            ]);
        }
    }
}
