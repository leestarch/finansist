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
        
        // \App\Models\User::factory(10)->create();
        // Create 10 records of customers
        Product::factory(10000)->hasProps(25)->create();
    }
}
