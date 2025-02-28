<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class CategoryService
{
    public static function getCategoryTree(
        bool $withSum = false, ?string $startDate = null,
        ?string $endDate = null, string $groupBy = 'daily', ?array $pizzeriaIds = null,
        ?array $contractorIds = null, ?string $purposeQuery = null
    ): array
    {
        if (!$startDate)
            $startDate = now()->startOfMonth()->toDateString();

        if (!$endDate)
            $endDate = now()->endOfMonth()->toDateString();

        $query = Category::query();

        if ($withSum) {
            $withDependencies = self::getDependencies($startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery);
            $query->with($withDependencies);
        }

        $categories = $query->get()->where('id', '!=',0);
        $result = [];

        foreach ($categories->whereNull('parent_id') as $rootCategory) {

            $rootCategory->totals = self::calculateDailyTotals(
                $rootCategory->id, $rootCategory->operations, $rootCategory->children, $groupBy, true
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
        int $categoryId, $operations, $children = null, string $groupBy = 'daily', bool $isRoot = false, array $rootTotals = []
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
                ? $totals[$dateKey]['sum'] += $operation->getAmountOfCategory($categoryId)
                : $totals[$dateKey]['sum'] -= $operation->getAmountOfCategory($categoryId);

        }

        if ($children instanceof Collection) {
            foreach ($children as $child) {
                $childTotals = self::calculateDailyTotals($child->id, $child->operations, $child->children, $groupBy, false, $rootTotals);
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
                        ($data['sum'] / $rootSumForDate) * 100,
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
                $category->id, $category->operations, $category->children, $groupBy, false, $rootTotals
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


    private static function getDependencies(
        $startDate, $endDate, ?array $pizzeriaIds, ?array $contractorIds, ?string $purposeQuery
    ):array
    {
        return [
            'operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.children.children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.children.children.children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.children.children.children.children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
            'children.children.children.children.children.children.operations' => self::operationQueryClosure(
                $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
            ),
        ];
    }


    private static function operationQueryClosure(
        $startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery
    ): callable
    {
        return function($q) use ($startDate, $endDate, $pizzeriaIds, $contractorIds, $purposeQuery) {
            $q->whereBetween('date_at', [$startDate, $endDate]);

            $q->where('sber_direction', Operation::DEBIT);

            $q->where('is_completed', 1);

            if($pizzeriaIds) {
                $q->whereIn('pizzeria_id', $pizzeriaIds);
            }

            if($contractorIds) {
                $q->whereHas('payeeContractor', function($q) use ($contractorIds) {
                    $q->whereIn('id', $contractorIds);
                });
            }

            if($purposeQuery) {
                $q->where('sber_paymentPurpose', 'like', "%$purposeQuery%");
            }
        };
    }
}