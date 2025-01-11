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
}
