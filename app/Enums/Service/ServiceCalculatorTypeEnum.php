<?php

namespace App\Enums\Service;

enum ServiceCalculatorTypeEnum: string
{
    case SquareMeter = 'square_meter';  // Калькулятор для расчета квадратного метра
    case Perimeter = 'perimeter';       // Калькулятор для расчета периметра

    /**
     * Возвращает массив [значение => название]
     */
    public static function list(): array
    {
        return [
            self::SquareMeter->value => 'Квадратный метр',
            self::Perimeter->value => 'Периметр',
        ];
    }

    /**
     * Возвращает название типа калькулятора на основе значения
     */
    public static function getName(?string $type): ?string
    {
        return self::list()[$type] ?? null;
    }
}