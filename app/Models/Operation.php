<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Operation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['categories', 'types'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_operations');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'operations_types');
    }
}
