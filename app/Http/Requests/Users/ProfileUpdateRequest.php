<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();
        return [
            'name' => 'required|string|unique:users,name,' . $userId,
            'email' => 'required|unique:users,email,' . $userId,
            'phone' => 'required|unique:users,phone,' . $userId,
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->clearPhone($this->input('phone', null));
        $this->merge(['phone' => $phone]);
    }

    private function clearPhone($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);
            return $phone ?: null;
        }

        return null;
    }

    public function messages(): array
    {
        return [
            'name' => $this->name . ' boshqa hodim uchun tanlangan, qayta kiriting',
            'email.unique' => $this->email . ' boshqa hodim uchun tanlangan, qayta kiriting',
            'phone.unique' => $this->phone . ' boshqa hodim uchun tanlangan, qayta kiriting',
        ];
    }
}
