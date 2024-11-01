<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Prop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        $this->call([
            TypeSeeder::class,
            OperationSeeder::class,
        ]);
    }
}
