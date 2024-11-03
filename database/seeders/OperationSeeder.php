<?php

namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100000; $i++) {
            $amount = fake()->numberBetween(-10000, 20000);
            $operation = Operation::query()->create([
                'amount' => $amount,
                'description' => fake()->sentence(),
                'is_completed' => fake()->boolean(),
                'date' => fake()->dateTimeBetween(now()->subMonths(2)->startOfMonth(), now()->addMonth()->startOfMonth()),
                'type' => $amount > 0 ? Operation::INCOME : Operation::EXPENSE,
            ]);
            $operation->types()->attach(\App\Models\Type::query()->inRandomOrder()->first());
            $operation->categories()->attach(\App\Models\Category::query()->inRandomOrder()->first());
        }
    }
}
