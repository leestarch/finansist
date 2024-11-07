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

    //todo посчитать отношения суммы в категории к сумме верхнего родителя
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
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.operations'  => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.children.operations'  => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
                'children.children.children.children.children.children.operations' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                },
            ]);
        }

        $categories = $query->get();

        $result = [];

        foreach ($categories->whereNull('parent_id') as $rootCategory) {
            $rootCategory->totals = self::calculateDailyTotals(
                $rootCategory->operations, $rootCategory->children, $groupBy, true
            );
            $rootCategory->children = self::buildTree($rootCategory->children, $groupBy, $rootCategory->totals);
            $result[] = $rootCategory;
        }

        return $result;
    }

    private static function calculateDailyTotals(
        $operations, $children = null, string $groupBy = 'daily', bool $isRoot = false, array $rootTotals = []
    ): array
    {
        $dailyTotals = [];
        $dateFormat = match ($groupBy) {
            'daily' => 'd-m-Y',
            'weekly' => 'W-o',
            'monthly' => 'm-Y',
            'quarterly' => 'Q-Y',
        };

        // Aggregate totals for this category's operations
        foreach ($operations as $operation) {
            $date = Carbon::parse($operation->date_at);
            $dateKey = $groupBy === 'quarterly' ? $date->quarter . '-' . $date->format('Y') : $date->format($dateFormat);

            if (!isset($dailyTotals[$dateKey])) {
                $dailyTotals[$dateKey] = ['sum' => 0, 'percentage_of_root' => 0];
            }
            $dailyTotals[$dateKey]['sum'] += $operation->sber_amountRub;
        }

        // Include totals from child categories
        if ($children instanceof Collection) {
            foreach ($children as $child) {
                $childTotals = self::calculateDailyTotals($child->operations, $child->children, $groupBy, false, $rootTotals);
                foreach ($childTotals as $date => $data) {
                    if (!isset($dailyTotals[$date])) {
                        $dailyTotals[$date] = ['sum' => 0, 'percentage_of_root' => 0];
                    }
                    $dailyTotals[$date]['sum'] += $data['sum'];
                }
            }
        }

        // Calculate the percentage of the root total for each date
        if (!$isRoot && !empty($rootTotals)) {
            foreach ($dailyTotals as $date => &$data) {
                $rootSumForDate = $rootTotals[$date]['sum'] ?? 0;
                $data['percentage_of_root'] = number_format($rootSumForDate > 0 ? ($data['sum'] / $rootSumForDate) * 100 : 0, 2);
            }
        }

        return $dailyTotals;
    }

    private static function buildTree(Collection $categories, string $groupBy, array $rootTotals = []): array
    {
        foreach ($categories as $category) {
            // Calculate totals for each child category with the root totals for percentage calculation
            $category->totals = self::calculateDailyTotals($category->operations, $category->children, $groupBy, false, $rootTotals);

            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children, $groupBy, $rootTotals);
                $category->children = collect($childTree);
            }
        }
        return $categories->toArray();
    }
}
