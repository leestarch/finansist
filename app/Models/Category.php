<?php

namespace App\Models;

use Carbon\Carbon;
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

    public static function getCategoryTree(bool $withSum = false, ?string $startDate = null, ?string $endDate = null)//: array
    {
        if (!$startDate) {
            $startDate = now()->startOfMonth()->toDateString();
        }
        if (!$endDate) {
            $endDate = now()->endOfMonth()->toDateString();
        }

        $query = Category::query()->with([
            'children',
            'children.children',
            'children.children.children',
            'children.children.children.children',
            'children.children.children.children.children',
            'children.children.children.children.children.children'
        ]);

        if ($withSum) {
//            $operations = Operation::query()
////                ->leftJoin('categories_operations', function ($join) {
////                    $join->on('categories_operations.operation_id', '=', 'operations.id');
////                })
//                ->whereDate('date', '>=', $startDate)
//                ->whereDate('date', '<=', $endDate)
//                ->get();
            $query->with([
                'children.operations',
                'children.children.operations',
                'children.children.children.operations',
                'children.children.children.children.operations',
                'children.children.children.children.children.operations',
                'children.children.children.children.children.children.operations',
            ]);
        }


        $categories = $query->get();

        $result = [];

        foreach ($categories->whereNull('parent_id') as $category) {
            $category->daily_totals = self::calculateDailyTotals($category->operations, $withSum);
            $category->children = self::buildTree($category->children, $withSum);
            $result[] = $category;
        }

        return $result;
    }

    private static function calculateDailyTotals($operations, $withSum): array
    {
        $dailyTotals = [];
        foreach ($operations as $operation) {
            $date = Carbon::parse($operation->date)->format('d-m-Y');
            if (!isset($dailyTotals[$date]))
                $dailyTotals[$date] = 0;
            $dailyTotals[$date] += $operation->amount;
        }
        $daily_totals = $dailyTotals;
        return $daily_totals;
    }

    private static function buildTree(Collection $categories, $withSum): array
    {
        foreach ($categories as $category) {
            $category->daily_totals = self::calculateDailyTotals($category->operations, $withSum);
            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children, $withSum);
                $category->children = collect($childTree);
            }
        }
        return $categories->toArray();
    }
}
