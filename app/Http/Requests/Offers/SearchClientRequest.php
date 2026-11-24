<?php

namespace App\Http\Requests\Offers;

use App\Helpers\PhoneNormalizer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Поиск клиента из таблицы clients по номеру телефона (форма заказа).
 */
class SearchClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isWorker();
    }

    protected function prepareForValidation(): void
    {
        $phone = PhoneNormalizer::clearPhoneNumber($this->input('phone'));

        // Если номер ввели целиком с кодом страны, отрезаем 998: в БД хранится 9 цифр
        if ($phone && strlen($phone) > 9 && str_starts_with($phone, '998')) {
            $phone = substr($phone, 3);
        }

        $this->merge(['phone' => $phone]);
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|min:3|max:9',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Telefon raqamini kiriting',
            'phone.min' => 'Kamida 3 ta raqam kiriting',
            'phone.max' => 'Telefon raqami juda uzun',
        ];
    }
}
