<?php

namespace App\Models;

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
}
