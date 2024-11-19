<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class OperationRuleStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'operation_type' => 'required|string|in:DEBIT,CREDIT',
            'contractor_ids' => 'required|array',
            'category_id' => 'required|integer',
            'purpose_expression' => 'nullable|string',
        ];
    }
}
