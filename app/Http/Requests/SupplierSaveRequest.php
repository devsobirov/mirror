<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|unique:suppliers,name,'. $this->route('supplier')?->id,
            'phone' => 'nullable',
            'phone2' => 'nullable',
            'active' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $phone = $this->clearPhone($this->input('phone', null));
        $phone2 = $this->clearPhone($this->input('phone2', null));
        $this->merge([
            'active' => $this->input('active', false),
            'phone' => $phone,
            'phone2' => $phone2,
        ]);
    }

    private function clearPhone($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);
            return $phone ?: null;
        }

        return null;
    }
}
