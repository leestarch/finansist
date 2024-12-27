<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
//    protected $with = ['categories', 'types'];

    const CREDIT = 'CREDIT';
    const DEBIT = 'DEBIT';

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_operations')
        ->as('pivot')->withPivot('sber_amountRub');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'operations_types');
    }

    public function payeeContractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'payee_contractor_id');
    }

    public function payerContractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'payer_contractor_id');
    }

    public function pizzeria(): BelongsTo
    {
        return $this->belongsTo(Pizzeria::class);
    }

    /**
     * @throws \Exception
     */
    public function handleCategories(array $data): void
    {
        if(array_sum($data) !== $this->sber_amountRub)
            throw new \Exception('Сумма разделения не равна сумме операции');

        $this->categories()->detach();

        foreach ($data as $categoryId => $amount) {
            $this->categories()->attach($categoryId, ['sber_amountRub' => $amount]);
        }
    }

    public function getFullAmount(): int
    {
        if($this->categories->count() > 1){
            return $this->categories->sum('pivot.sber_amountRub');
        }
        return $this->sber_amountRub;
    }

    public function isSplit(): bool
    {
        return $this->categories->count() > 1;
    }

    public function getAmountOfCategory(int $categoryId): int
    {
        if($this->isSplit()) {
            return $this->categories->where('id', $categoryId)->first()->pivot->sber_amountRub;
        } else {
            return $this->sber_amountRub;
        }
    }

    public function getDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {

        if ($type = $filters['type'] ?? null) {
            $query->whereHas('types', function ($subQuery) use ($type) {
                $subQuery->where('name', $type['name']);
            });
        }

        if($sum = $filters['sum'] ?? null) {
            $query->where('sber_amountRub', 'like', '%'. $sum.'%');
        }

        if ($pizzeriaId = $filters['pizzeriaId'] ?? null) {
            $query->where('pizzeria_id', $pizzeriaId);
        }

        if ($pizzeriaIds = $filters['pizzeriaIds'] ?? null) {
            $query->whereIn('pizzeria_id', $pizzeriaIds);
        }

        if ($sberDirection = $filters['sberDirection'] ?? null) {
            if(is_array($sberDirection))
                $sberDirection = $sberDirection['value'];

            $query->where('sber_direction', $sberDirection);
        }

        if($contractorId = $filters['payeeContractorId'] ?? null){
            $query->where('payee_contractor_id', $contractorId);
        }

        if($contractorIds = $filters['payeeContractorIds'] ?? null){
            $query->wherein('payee_contractor_id', $contractorIds);
        }

        if ($category = $filters['category'] ?? null) {
            $query->whereHas('categories', function ($subQuery) use ($category) {
                $subQuery->where('name', $category['name']);
            });
        }

        if($contractorIds = $filters['contractorIds'] ?? null){
            $query->whereHas('payeeContractor', function($q) use ($contractorIds) {
                $q->whereIn('id', $contractorIds);
            });
        }

        if ($categoryId = $filters['categoryId'] ?? null) {
            $query->whereHas('categories', function (Builder $subQuery) use ($categoryId) {
                $subQuery->where('categories.id', (int) $categoryId);
            });
        }

        if ($categoryIds = $filters['categoryIds'] ?? null) {
            if(in_array(0, $categoryIds))
                $query->whereDoesntHave('categories');
            else
                $query->whereHas('categories', function (Builder $subQuery) use ($categoryIds) {
                    $subQuery->whereIn('categories.id', (array) $categoryIds);
                });
        }

        if ($category = $filters['categoryQuery'] ?? null) {
            $query->whereHas('categories', function ($subQuery) use ($category) {
                $subQuery->where('name', 'like', "%$category%");
            });
        }

        if($purposeQuery = $filters['purposeQuery'] ?? null){
            $query->where('sber_paymentPurpose', 'like', "%$purposeQuery%");
        }

        if ($dateFrom = $filters['dateFrom'] ?? null) {
            $query->whereDate('date_at', '>=', $dateFrom);
        }

        if ($dateTo = $filters['dateTo'] ?? null) {
            $query->whereDate('date_at', '<=', $dateTo);
        }

        if ($dateAt = $filters['dateAt'] ?? null) {
            $query->whereDate('date_at', '=', Carbon::parse($dateAt));
        }

        if($isSplit = $filters['isSplit'] ?? null){
            $isSplit = $isSplit === 'true';

//            if ($isSplit) {
//                $query->has('categories', '>', 1);
//            } else {
//                $query->where(function ($query) {
//                    $query->doesntHave('categories')
//                        ->orHas('categories', '=', 1);
//                });
//            }
//            $query->whereRaw(
//                $isSplit
//                    ? '(SELECT COUNT(*) FROM categories_operations WHERE categories_operations.operation_id = operations.id) > 1'
//                    : '(SELECT COUNT(*) FROM categories_operations WHERE categories_operations.operation_id = operations.id) <= 1'
//            );
        }

        return $query;
    }
}
