<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
      'columns' => 'float',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class, 'categories_operations');
    }
    public static function getCategoryTree(bool $withSum = false): array
    {
        $query = Category::with('children')->whereNull('parent_id');

        if ($withSum) {
            $query->withSum('operations', 'amount');
        }

        $categories = $query->get();

        return self::buildTree($categories, $withSum);
    }

    private static function buildTree(Collection $categories, bool $withSum = false): array
    {
        foreach ($categories as $category) {
            if ($withSum) {
                $category->total_amount = $category->operations_sum_amount ?? 0;
                unset($category->operations_sum_amount);
            }

            if ($category->children->isNotEmpty()) {
                $category->children = self::buildTree($category->children, $withSum);
            }
        }

        return $categories->toArray();
    }
}
