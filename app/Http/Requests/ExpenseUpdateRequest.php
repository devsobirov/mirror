<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:0',
            'price_usd' => 'nullable|integer|min:0',
            'created_at' => 'required|date',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'description' => 'nullable|string'
        ];
    }
}
