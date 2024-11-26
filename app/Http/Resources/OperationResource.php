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
            'is_completed' => (bool) $this->is_completed,
            'pizzeria_id' => $this->pizzeria_id,
            'payee_contractor_id' => $this->payee_contractor_id,
            'date_at' => $this->date_at,
//            'date_at' => Carbon::parse($this->date_at)->format('d-m-Y'),
            'is_manual' => (bool)$this->is_manual,
            'types' => implode(', ', $this->types->pluck('name')->toArray()),
            'categories' => $this->whenLoaded(
                'categories',
                fn() => $this->categories->map(fn($category)=> [
                    'id' => $category->id,
                    'name' => $category->name,
                    'sber_amountRub' => $this->categories->count() > 1
                        ? $category->pivot->sber_amountRub
                        : $this->sber_amountRub,
                ])
            ),
            'payee_contractor' => $this->whenLoaded(
                'payeeContractor',
                fn() => new ContractorResource($this->payeeContractor)
            ),
        ];
    }
}
