<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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
        return $this->belongsToMany(Operation::class);
    }
    public static function getCategoryTree(): array
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return self::buildTree($categories);
    }

    private static function buildTree(Collection $categories): array
    {
        foreach ($categories as $category) {
            if ($category->children->isNotEmpty()) {
                $category->children = self::buildTree($category->children);
            }
        }

        return $categories->toArray();
    }
}
