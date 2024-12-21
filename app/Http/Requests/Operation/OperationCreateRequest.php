<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class OperationCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pizzeria_id' => ['required', 'integer'],
            'sber_amountRub' => ['required', 'numeric'],
            'sber_paymentPurpose' => ['required', 'string'],
            'sber_direction' => ['required', 'string', 'in:DEBIT,CREDIT'],
            'payer_contractor_id' => ['required', 'integer'],
            'payee_contractor_id' => ['required', 'integer'],
            'is_completed' => ['required', 'boolean'],
            'date_at' => ['required', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*.id' => ['required', 'integer'],
            'categories.*.sber_amountRub' => ['required', 'integer'],
        ];
    }
}
