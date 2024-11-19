<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PizzeriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'inn' => $this->inn,
            'planfact_cash_account' => $this->planfact_cash_account,
            'dodois_unit_uid' => $this->dodois_unit_uid,
            'user_id' => $this->user_id,
            'to_user_id' => $this->to_user_id,
            'budget' => $this->budget,
            'agentIds' => $this->agentIds,
        ];
    }
}
