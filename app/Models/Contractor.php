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
        if($q =$filters['q'] ?? null)
            $query->where('full_name', 'like', "%$q%")
                ->orWhere('inn_kpp', 'like', "%" . $filters['q'] . "%");

        if($innKpp = $filters['innKpp'] ?? null)
            $query->where('inn_kpp', 'like', "%$innKpp%");

        if($name = $filters['name'] ?? null)
            $query->where('full_name', 'like', "%$name%");

        if($ids = $filters['ids'] ?? null)
            $query->whereIn('id', $ids);

        return $query;
    }
}
