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
            $model = Category::updateOrCreate(
                [
                    'name' => $category->title,
                ],
                [
                    'category_type' => $category->operationCategoryType,
                ]
            );

            $category->model_id = $model->id;
        }

        foreach ($categories->items as $category) {
            if ($category->parentOperationCategoryId) {
                $parentModelId = null;

                foreach ($categories->items as $item) {
                    if ($item->operationCategoryId == $category->parentOperationCategoryId) {
                        $parentModelId = $item->model_id;
                        break;
                    }
                }

                Category::where('id', $category->model_id)
                    ->update(['parent_id' => $parentModelId]);
            }
        }
    }
}
