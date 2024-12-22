<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class CategorySaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:3|unique:service_categories,title,'. $this->route('serviceCategory')?->id,
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'Ushbu nom boshqa xizmat turi uchun kiritilgan, boshqani kiriting'
        ];
    }
}
