<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LoadBudgetCategories extends Command
{
    protected $signature = 'load.budget.categories';

    public function handle()
    {
        $categories = json_decode(file_get_contents(base_path('categories.json')));

        foreach ($categories->items as $category) {
            $parentModel = Category::firstOrCreate([
                'name' => $category->title,
            ],[
                'category_type' => $category->operationCategoryType,
            ]);

            foreach ($categories->items as $subCategory) {
                if ($subCategory->parentOperationCategoryId === $category->operationCategoryId) {
                    Category::firstOrCreate([
                        'name' => $subCategory->title,
                    ], [
                        'category_type' => $subCategory->operationCategoryType, 'parent_id' => $parentModel->id,
                    ]);
                }
            }
        }
    }
}
