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
    public static function getCategoryTree(bool $withSum = false, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Category::with('children');
        if ($withSum) {
            $query->with(['operations' => function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('date', '>=', $startDate);
                }
                if ($endDate) {
                    $q->whereDate('date', '<=', $endDate);
                }
            }]);
        }

        $categories = $query->whereNull('parent_id')->get();
        return self::buildTree($categories, $withSum);
    }

    private static function buildTree(Collection $categories, bool $withSum = false): array
    {
        foreach ($categories as $category) {
            $categoryTotal = $withSum ? $category->operations->sum(function ($operation) {
                return is_numeric($operation->amount) ? $operation->amount : 0;
            }) : 0;

            if ($category->children->isNotEmpty()) {
                $category->children = collect(self::buildTree($category->children, $withSum));

                foreach ($category->children as $child) {
                    $categoryTotal += $child['total_amount'] ?? 0;
                }
            }

            $category->total_amount = $withSum ? $categoryTotal : null;
        }

        return $categories->toArray();
    }
}
