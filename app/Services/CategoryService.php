<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class CategoryService
{
    public static function getCategoryTree(
        bool $withSum = false, ?string $startDate = null,
        ?string $endDate = null, string $groupBy = 'daily', ?int $pizzeriaId = null,
    ): array
    {
        if (!$startDate)
            $startDate = now()->startOfMonth()->toDateString();

        if (!$endDate)
            $endDate = now()->endOfMonth()->toDateString();

        $query = Category::query();
        if ($withSum) {
            $withDependencies = self::getDependencies($startDate, $endDate, $pizzeriaId);
            $query->with($withDependencies);
        }

        $categories = $query->get();
        $result = [];

        foreach ($categories->whereNull('parent_id') as $rootCategory) {

            $rootCategory->totals = self::calculateDailyTotals(
                $rootCategory->operations, $rootCategory->children, $groupBy, true
            );
            $children = self::buildTree($rootCategory->children, $groupBy, $rootCategory->totals);
            // unset relations иначе релейшен будет перекрывать массив выше
            unset($rootCategory->children);
            $rootCategory->children = array_values($children);

            $result[] = $rootCategory;
        }

        return $result;
    }

    private static function calculateDailyTotals(
        $operations, $children = null, string $groupBy = 'daily', bool $isRoot = false, array $rootTotals = []
    ): array
    {
        $totals = [];
        $dateFormat = match ($groupBy) {
            'daily' => 'd-m-Y',
            'weekly' => 'W-o',
            'monthly' => 'm-Y',
            'quarterly' => 'Q-Y',
        };

        foreach ($operations as $operation) {
            $date = Carbon::parse($operation->date_at);
            $dateKey = $groupBy === 'quarterly' ? $date->quarter . '-' . $date->format('Y') : $date->format($dateFormat);

            if (!isset($totals[$dateKey])) {
                $totals[$dateKey] = ['sum' => 0, 'percentage_of_root' => 0];
            }

            $operation->sber_direction === Operation::CREDIT
                ? $totals[$dateKey]['sum'] -= $operation->sber_amountRub
                : $totals[$dateKey]['sum'] += $operation->sber_amountRub;

        }

        if ($children instanceof Collection) {
            foreach ($children as $child) {
                $childTotals = self::calculateDailyTotals($child->operations, $child->children, $groupBy, false, $rootTotals);
                foreach ($childTotals as $date => $data) {
                    if (!isset($totals[$date])) {
                        $totals[$date] = ['sum' => 0, 'percentage_of_root' => 0];
                    }
                    $totals[$date]['sum'] += $data['sum'];
                }
            }
        }

        if (!$isRoot && !empty($rootTotals)) {
            foreach ($totals as $date => &$data) {
                $rootSumForDate = $rootTotals[$date]['sum'] ?? 0;
                $data['percentage_of_root'] =
                    number_format(
                        $rootSumForDate > 0 ? ($data['sum'] / $rootSumForDate) * 100 : 0,
                        2
                    );
            }
        }
        return $totals;
    }

    private static function buildTree(Collection $categories, string $groupBy, array $rootTotals = []): array
    {
        $newCategories = $categories->filter(function ($category) use ($groupBy, $rootTotals) {
            $category->totals = self::calculateDailyTotals(
                $category->operations, $category->children, $groupBy, false, $rootTotals
            );

            if ($category->children->isNotEmpty()) {
                $childTree = self::buildTree($category->children, $groupBy, $rootTotals);
                // unset relations иначе релейшен будет перекрывать массив выше
                unset($category->children);
                $category->children = collect(array_values($childTree));
            }

            return !(
                empty($category->totals) &&
                $category->children->isEmpty()
            );
        });

        return $newCategories->toArray();
    }


    private static function getDependencies($startDate, $endDate, ?int $pizzeriaId):array
    {
        return [
            'operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.children.children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.children.children.children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.children.children.children.children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
            'children.children.children.children.children.children.operations' =>
                function($q) use ($startDate, $endDate, $pizzeriaId) {
                    $q->whereBetween('date_at', [$startDate, $endDate]);
                    if($pizzeriaId) {
                        $q->where('pizzeria_id', $pizzeriaId);
                    }
                },
        ];
    }
}