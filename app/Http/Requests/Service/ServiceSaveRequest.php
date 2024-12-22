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
            'name' => 'required|string|max:255|min:3|unique:goods,name,'. $this->route('service')?->id,
            'category_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'status' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ];
    }
}
