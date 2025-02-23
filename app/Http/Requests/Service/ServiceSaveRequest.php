<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3|unique:services,name,'. $this->route('service')?->id,
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'status' => 'boolean',
            'unit' => 'required|string|min:0',
            'price' => 'required|numeric|min:0',
            'margin' => 'nullable|numeric|min:0',
            'active' => 'boolean',
            'calculator_id' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->post('margin')) {
            $this->merge(['margin' => 0]);
        }

        $this->merge(['active' => !empty($this->post('active'))]);
    }
}
