<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route()->parameter('user')?->id ?? $this->user()->id;

        return [
            'name' => 'required|string|unique:users,name,' . $userId,
            'email' => 'nullable|unique:users,email,' . $userId,
            'phone' => 'required|unique:users,phone,' . $userId,
            'role' => 'nullable|numeric'
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
            'name.unique' => $this->name . ' boshqa hodim uchun tanlangan, qayta kiriting',
            'email.unique' => $this->email . ' boshqa hodim uchun tanlangan, qayta kiriting',
            'phone.unique' => $this->phone . ' boshqa hodim uchun tanlangan, qayta kiriting',
        ];
    }
}
