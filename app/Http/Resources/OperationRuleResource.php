<?php

namespace App\Http\Resources;

use App\Http\Resources\Category\MinifiedCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationRuleResource extends JsonResource
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
            'category_id' => $this->category_id,
            'contractor_id' => $this->contractor_id,
            'operation_type' => $this->operation_type,
            'purpose_expression' => $this->purpose_expression,
            'name' => $this->name,
            'contractor' => $this->whenLoaded('contractor', fn() => new ContractorResource($this->contractor)),
            'category' => $this->whenLoaded('category', fn() => new MinifiedCategoryResource($this->category)),
        ];
    }
}
