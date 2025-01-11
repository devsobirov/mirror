<?php

namespace App\Enums\Finance;

enum DebtStatusEnum: int
{
    case Pending = 1;    // Ожидание
    case Paid = 2;       // Оплачено
    case Cancelled = 3;  // Отменено

    /**
     * Возвращает массив [значение => название]
     */
    public static function list(): array
    {
        return [
            self::Pending->value => 'Ожидание',
            self::Paid->value => 'Оплачено',
            self::Cancelled->value => 'Отменено',
        ];
    }

    /**
     * Возвращает название статуса на основе значения
     */
    public static function getName(int $status): string
    {
        return self::list()[$status] ?? 'Неизвестный статус';
    }
}
