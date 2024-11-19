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
        return $this->belongsToMany(Category::class, 'categories_operations');
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

    public function scopeFilter(Builder $query, array $data)
    {

    }
}
