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

    protected $guarded = ['id'];
//    protected $with = ['categories', 'types'];

    const CREDIT = 'CREDIT';
    const DEBIT = 'DEBIT';

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_operations')
        ->as('pivot');
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

//    public function getAmountAttribute($value): string
//    {
//        return is_numeric($value) ? number_format($value, 2, '.', ' ') : $value;
//    }

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

        if($contractorIds = $filters['payee_contractor_id'] ?? null){
            $query->whereIn('payee_contractor_id', $contractorIds);
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

        return $query;
    }
}
