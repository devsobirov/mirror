<?php

namespace App\Traits;

/**
 * @property null|string $phone_number
 * @property null|string $phone_number2
 */
trait HasPhoneNumbers
{
    public function getPhoneNumberAttribute()
    {
        // Проверяем, если номер существует, то форматируем его
        $phone = $this->attributes['phone']; // Получаем номер телефона из атрибутов модели

        // Если номер существует и состоит из 9 цифр, применяем формат
        if ($phone && preg_match('/^(\d{9})$/', $phone)) {
            // Форматируем номер: +998(99) 999-99-99
            return "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
        }

        // Если номер не соответствует формату, возвращаем его как есть или можно вернуть null
        return $phone;
    }

    public function getPhoneNumber2Attribute()
    {
        // Проверяем, если номер существует, то форматируем его
        $phone = $this->attributes['phone2']; // Получаем номер телефона из атрибутов модели

        if ($phone && preg_match('/^(\d{9})$/', $phone)) {
            // Форматируем номер: +998(99) 999-99-99
            return "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
        }

        // Если номер не соответствует формату, возвращаем его как есть или можно вернуть null
        return $phone;
    }
}
