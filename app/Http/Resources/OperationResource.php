<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'date' => $this->date,
            'categories' => implode(', ',$this->categories->pluck('name')->toArray()),
            'types' => implode(', ', $this->types->pluck('name')->toArray()),
        ];
    }
}
