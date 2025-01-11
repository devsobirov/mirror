<?php

namespace App\Helpers;

class PhoneNormalizer
{
    public static function clearPhoneNumber($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);
            return $phone ?: null;
        }

        return null;
    }

    public static function formatPhoneNumber($phone): ?string
    {
        // Если номер существует и состоит из 9 цифр, применяем формат
        if ($phone && preg_match('/^(\d{9})$/', $phone)) {
            // Форматируем номер: +998(99) 999-99-99
            return "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
        }

        // Если номер не соответствует формату, возвращаем его как есть или можно вернуть null
        return $phone;
    }
}
