<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3|unique:goods,name,'. $this->route()->parameter('good')?->id,
            'category_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'status' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ];
    }
}
