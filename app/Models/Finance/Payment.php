<?php

namespace App\Models\Finance;

use App\Enums\Finance\PaymentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = false;

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class, 'debt_id', 'id');
    }

    public function getTypeNameAttribute(): string
    {
        return PaymentTypeEnum::getName($this->type);
    }

    public static function calcLeftPaymentAmount(Debt $debt, int|float $paymentAmount): int|float
    {
        $left = $debt->left_amount - $paymentAmount;
        return max(0, $left);
    }
}
