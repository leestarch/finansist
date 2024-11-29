<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class OperationSeedFromAPIRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.id' => ['required', 'int'],
            'data.*.pizzeria_id' => ['required', 'int'],
            'data.*.date_at' => ['required', 'date'],
            'data.*.sber_amountRub' => ['required', 'int'],
            'data.*.sber_direction' => ['required', 'string'],
            'data.*.sber_paymentPurpose' => ['required', 'string'],
            'data.*.sber_operationId' => ['required', 'int'],
            'data.*.payer_contractor_id' => ['required', 'int'],
            'data.*.payee_contractor_id' => ['required', 'int'],
            'data.*.is_completed' => ['required', 'bool'],
            'data.*.is_manual' => ['required', 'bool'],
            'data.*.deleted_at' => ['nullable', 'date'],
            'data.*.created_at' => ['nullable', 'date'],
            'data.*.updated_at' => ['nullable', 'date'],
        ];
    }
}
