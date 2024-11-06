<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
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
    //

    public static function getCategoryTree(bool $withSum = false, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!$startDate) {
            $startDate = now()->startOfMonth()->toDateString();
        }
        if (!$endDate) {
            $endDate = now()->endOfMonth()->toDateString();
        }

        // TODO взять коллекцию операций за период и daily_sum считать из коллекции
        $operations = Operation::query()->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();


        $query = Category::with('children');

        if ($withSum) {
            $query->with('operations');
//            $query->with(['operations' => function ($q) use ($startDate, $endDate) {
//                $q->selectRaw('category_id, DATE_FORMAT(DATE(date), "%d-%m-%Y") as operation_date, SUM(amount) as daily_sum')
//                    ->whereDate('date', '>=', $startDate)
//                    ->whereDate('date', '<=', $endDate)
//                    ->groupBy('operation_date', 'category_id');
//            }]);
        }

        $categories = $query->whereNull('parent_id')->select(['id', 'name', 'parent_id'])
            ->get();

        return self::buildTree($categories, $withSum);
    }

    private static function buildTree(Collection $categories, bool $withSum = false): array
    {
        foreach ($categories as $category) {
//            if ($withSum) {
//                $category->operations->each(function ($operation) {
//                    $operation->operation_date = $operation->operation_date ?? Carbon::parse($operation->date)->format('d-m-Y');
//                    $operation->daily_sum = $operation->daily_sum ?? $operation->amount;
//                });
//            }

            $dailyTotals = $withSum ? $category->operations->groupBy('operation_date')->map(function ($dayOperations) {
                return $dayOperations->sum('daily_sum');
            })->toArray() : [];

            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children, $withSum);
                $category->children = collect($childTree);

                foreach ($childTree as $child) {
                    if ($withSum && isset($child['daily_totals'])) {
                        foreach ($child['daily_totals'] as $date => $sum) {
                            $dailyTotals[$date] = ($dailyTotals[$date] ?? 0) + $sum;
                        }
                    }
                }
            }

            $category->daily_totals = $withSum ? $dailyTotals : null;
        }
        return $categories->toArray();
    }
}
