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
            'amount' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'is_completed' => ['required', 'boolean'],
            'date' => ['required', 'date'],
            'type' => ['required'],
            'category' => ['required'],
        ];
    }
}
