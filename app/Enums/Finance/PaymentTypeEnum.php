<?php

namespace App\Enums\Finance;

enum PaymentTypeEnum: int
{
    case Cash = 1;       // Наличные
    case Card = 2;       // Карта
    case CardTransfer = 3; // Перевод на карту
    case BankTransfer = 4; // Банковский перевод

    /**
     * Возвращает массив [значение => название]
     */
    public static function list(): array
    {
        return [
            self::Cash->value => 'Наличные',
            self::Card->value => 'Карта',
            self::CardTransfer->value => 'Перевод на карту',
            self::BankTransfer->value => 'Банковский перевод',
        ];
    }

    public static function getName(int $status): string
    {
        return self::list()[$status] ?? 'Другое';
    }
}
