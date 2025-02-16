<?php

namespace App\Models\Finance;

use App\Enums\Finance\DebtStatusEnum;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $paid_amount
 * @property int $left_amount
 */
class Debt extends Model
{
    protected $guarded = false;
    protected $casts = [
        'payment_date' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'debt_id');
    }

    public function scopeByStatus(Builder $query, int|array $status): Builder
    {
        $status = is_array($status) ? $status : [$status];
        return $query->when(!empty($status), fn($q) => $q->whereIn('status', $status));
    }

    public function getStatusNameAttribute(): string
    {
        return DebtStatusEnum::getName($this->status);
    }

    public function getPaidAmountAttribute(): int
    {
        return $this->payments->sum('amount');
    }

    public function getLeftAmountAttribute(): int
    {
        $left = $this->amount - $this->paid_amount;
        return max($left, 0);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', DebtStatusEnum::Pending->value);
    }

    public function scopeByPaymentDate(Builder $query, ?string $dateFrom, ?string $dateTo): Builder
    {
        return $query->when($dateFrom, fn($q) => $q->where('payment_date', '>=', $dateFrom))
                     ->when($dateTo, fn($q) => $q->where('payment_date', '<=', $dateTo));
    }

    public function scopeByPaymentDateFrom(Builder $query, ?string $dateFrom): Builder
    {
        return $query->when($dateFrom, fn($q) => $q->where('payment_date', '>=', $dateFrom));
    }

    public function scopeByPaymentDateTo(Builder $query, ?string $dateTo): Builder
    {
        return $query->when($dateTo, fn($q) => $q->where('payment_date', '<=', $dateTo));
    }

    public function scopeByClient(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->whereHas('client', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('phone2', 'like', "%{$search}%");
            });
        });
    }
}
