<?php

namespace App\Enums\Offer;

enum OfferStatusEnum: int
{
    case Draft = 1;      // Черновик
    case Paid = 5;       // Оплачен
    case Completed = 7;  // Завершен

    /**
     * Возвращает массив [значение => название]
     */
    public static function list(): array
    {
        return [
            self::Draft->value => 'Chernovik',
            self::Paid->value => 'To\'langan',
            self::Completed->value => 'Yakunlangan',
        ];
    }

    /**
     * Возвращает название статуса на основе значения
     */
    public static function getName(?int $status): string
    {
        return self::list()[$status] ?? 'Неизвестный статус';
    }

    /**
     * Возвращает цвет бейджа для статуса
     */
    public static function getColor(?int $status): string
    {
        return match($status) {
            self::Draft->value => 'blue',
            self::Paid->value => 'green',
            self::Completed->value => 'purple',
            default => 'secondary'
        };
    }
} 