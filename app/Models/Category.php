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

    // TODO  для каждого чилдрена посчитать посчитать отношение к корневному родительскому элементу

    public static function getCategoryTree(bool $withSum = false, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!$startDate) {
            $startDate = now()->startOfMonth()->toDateString();
        }
        if (!$endDate) {
            $endDate = now()->endOfMonth()->toDateString();
        }

        $query = Category::query()->with(['children']);

        if ($withSum) {
            $query->with(['operations' => function ($q) use ($startDate, $endDate) {
                $q->whereDate('date', '>=', $startDate)
                    ->whereDate('date', '<=', $endDate)
                    ->get();
            }]);
        }
        $categories = $query->get();

        foreach ($categories as $category) {
            if ($withSum) {
                $dailyTotals = [];
                foreach ($category->operations as $operation) {
                    $date = Carbon::parse($operation->date)->format('d-m-Y');
                    if (!isset($dailyTotals[$date]))
                        $dailyTotals[$date] = 0;
                    $dailyTotals[$date] += $operation->amount;
                }
                $category->daily_totals = $dailyTotals;
            }
        }

        return self::buildTree($categories);
    }

    private static function buildTree(Collection $categories): array
    {
        foreach ($categories as $category) {
            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children);
                $category->children = collect($childTree);
            }
        }
        return $categories->toArray();
    }
}
