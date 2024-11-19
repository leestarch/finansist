<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Contractor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function pizzerias(): BelongsToMany
    {
        return $this->belongsToMany(Pizzeria::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {

        if(isset($filters['q'])) {
            $query->where('full_name', 'like', "%". $filters['q']."%")
                ->orWhere('inn_kpp', 'like', "%". $filters['q']."%");
        }
        if(isset($filters['innKpp']))
            $query->where('inn_kpp', 'like', "%". $filters['innKpp']."%");

        if(isset($filters['name']))
            $query->where('full_name', 'like', "%". $filters['name']."%");

        return $query;
    }
}
