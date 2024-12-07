<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|unique:warehouses,name,'. $this->route('warehouse')?->id,
            'active' => 'boolean',
            'comment' => 'nullable|string|max:255'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['active' => $this->input('active', false)]);
    }
}
