<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'sber_amountRub' => $this->sber_amountRub,
            'sber_paymentPurpose' => $this->sber_paymentPurpose,
            'is_completed' => $this->is_completed,
            'date_at' => Carbon::parse($this->date_at)->format('d-m-Y'),
            'categories' => implode(', ',$this->categories->pluck('name')->toArray()),
            'types' => implode(', ', $this->types->pluck('name')->toArray()),
        ];
    }
}
