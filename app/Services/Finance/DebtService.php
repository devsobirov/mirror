<?php

namespace App\Services\Finance;

use App\Enums\Finance\DebtStatusEnum;
use App\Models\Finance\Debt;

class DebtService
{
    /**
     * Проверяет, полностью ли оплачен долг
     * 
     * @param Debt $debt Объект долга
     * @return bool Возвращает true если долг полностью оплачен, false в противном случае
     */
    public static function checkDebtFullyPaid(Debt $debt): bool
    {
        return $debt->paid_amount >= $debt->amount;
    }

    /**
     * Обновляет статус долга
     * 
     * @param Debt $debt Объект долга
     * @return int|updateDebtStatus Возвращает обновленный статус долга
     */
    public static function updateDebtStatus(Debt $debt): int|updateDebtStatus
    {
        if (self::checkDebtFullyPaid($debt)) {
            $debt->update(['status' => DebtStatusEnum::Paid->value]);
        }

        return $debt->status;
    }

    public static function updateDebtRating(Debt $debt, int $rating): ?float
    {
        $avg = $debt->payments->where('rating', '>', 0)->avg('rating');
        if ($avg) {
            $debt->update(['rating' => $avg]);
        }
        return $debt->rating;
    }
}

