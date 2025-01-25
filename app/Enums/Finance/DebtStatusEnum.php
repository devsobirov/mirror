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
            self::Pending->value => 'Jarayonda',
            self::Paid->value => 'To\'langan',
            self::Cancelled->value => 'Bekor qilingan',
        ];
    }

    /**
     * Возвращает название статуса на основе значения
     */
    public static function getName(int $status): string
    {
        return self::list()[$status] ?? 'No\'ma\'lum status';
    }
}
