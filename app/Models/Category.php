<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isInstanceOf;

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

    public static function getCategoryTree(
        bool $withSum = false, ?string $startDate = null, ?string $endDate = null, string $groupBy = 'daily'
    ): array
    {
        if (!$startDate) {
            $startDate = now()->startOfMonth()->toDateString();
        }
        if (!$endDate) {
            $endDate = now()->endOfMonth()->toDateString();
        }

        $query = Category::query();

        if ($withSum) {
            $query->with([
                'operations'  => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.operations'  => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.children.operations'  => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
                'children.children.children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                },
            ]);
        }


        $categories = $query->get();

        $result = [];


        //todo посчитать отношения суммы за день в категории к сумме верхнего родителя

        foreach ($categories->whereNull('parent_id') as $category) {
            $category->daily_totals = self::calculateDailyTotals($category->operations,  $category->children, $groupBy);
            $category->children = self::buildTree($category->children, $groupBy);
            $result[] = $category;
        }

        return $result;
    }

    private static function calculateDailyTotals($operations,  $children = null, string $groupBy = 'daily'): array
    {
        $dailyTotals = [];

        $dateFormat = match ($groupBy) {
            'daily' => 'd-m-Y',
            'weekly' => 'W-o',
            'monthly' => 'm-Y',
            'quarterly' => 'Q-Y',
        };
        foreach ($operations as $operation) {
            $date = Carbon::parse($operation->date);

            if ($groupBy === 'quarterly') {
                $dateKey = $date->quarter . '-' . $date->format('Y');
            } else {
                $dateKey = $date->format($dateFormat);
            }

            if (!isset($dailyTotals[$dateKey]))
                $dailyTotals[$dateKey] = 0;
            $dailyTotals[$dateKey] += $operation->amount;
        }

        if ($children instanceof Collection) {
            foreach ($children as $child) {
                $childTotals = self::calculateDailyTotals($child->operations, $child->children, $groupBy);
                foreach ($childTotals as $date => $amount) {
                    if (!isset($dailyTotals[$date])) {
                        $dailyTotals[$date] = 0;
                    }
                    $dailyTotals[$date] += $amount;
                }
            }
        }
        return $dailyTotals;
    }

    private static function buildTree(Collection $categories, string $groupBy): array
    {
        foreach ($categories as $category) {
            $category->daily_totals = self::calculateDailyTotals($category->operations, $category->children, $groupBy);

            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children, $groupBy);
                $category->children = collect($childTree);
            }
        }
        return $categories->toArray();
    }
}
