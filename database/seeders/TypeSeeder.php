<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = Category::query()->get()->pluck('category_type')->unique()->values();
        foreach ($types as $type) {
            \App\Models\Type::query()->create([
                'name' => $type,
            ]);
        }
    }
}
