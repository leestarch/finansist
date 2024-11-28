<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedCategories extends Command
{
    protected $signature = 'seed.categories {--from-sql} {--from-json}';

    public function handle(): void
    {
        DB::table('categories')->truncate();

        if($this->option('from-sql')){
            $this->fromSql();
        }
        if($this->option('from-json')) {
            $this->fromJson();
        }

    }

    private function fromSql(): void
    {
        $sqlFilePath = storage_path('app/private/categories.sql');
        $sqlContent = file_get_contents($sqlFilePath);
        DB::unprepared($sqlContent);
    }

    private function fromJson(): void
    {
        Category::query()->firstOrCreate([
            'id' => 0,
            'name' =>  'Без категории',
        ]);

        $categories = json_decode(file_get_contents(base_path('categories.json')));

        foreach ($categories->items as $category) {
            $model = Category::query()->firstOrCreate([
                'name' => $category->title,
            ]);

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

                Category::query()->where('id', $category->model_id)
                    ->update(['parent_id' => $parentModelId]);
            }
        }
    }
}
