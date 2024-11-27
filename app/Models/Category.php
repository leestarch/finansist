<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function geyNestedChildren(): array
    {
        $ids = [];
        $this->load('children');

        $getChildrenIds = function ($category) use (&$ids, &$getChildrenIds) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                $getChildrenIds($child);
            }
        };
        $getChildrenIds($this);
        return $ids;
    }

}
