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
        for ($i = 1; $i <= 3000; $i++) {
            $amount = fake()->numberBetween(1, 20000);
            $operation = Operation::query()->create([
                'pizzeria_id' => fake()->numberBetween(1,8),
                'account_number' => fake()->creditCardNumber(),
                'contractor_id' => fake()->numberBetween(1,900),
                'sber_amountRub' => $amount,
                'sber_paymentPurpose' => fake()->sentence(),
                'is_completed' => fake()->boolean(),
                'date_at' => fake()->dateTimeBetween(now()->startOfMonth(), now()->addMonths(2)->startOfMonth()),
                'sber_direction' => $amount > 0 ? Operation::INCOME : Operation::EXPENSE,
            ]);
            $operation->types()->attach(\App\Models\Type::query()->inRandomOrder()->first());
            $operation->categories()->attach(\App\Models\Category::query()->inRandomOrder()->first());
        }
    }
}
