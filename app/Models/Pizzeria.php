<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pizzeria extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'agentIds' => 'array',
    ];

    public function contractors(): BelongsToMany
    {
        return $this->belongsToMany(Contractor::class);
    }
}
