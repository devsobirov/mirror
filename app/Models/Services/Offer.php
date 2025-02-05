<?php

namespace App\Models\Services;

use App\Enums\Offer\OfferStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPhoneNumbers;
use App\Models\User;

class Offer extends Model
{
    use HasPhoneNumbers;

    protected $guarded = [];

    protected $casts = [
        'status' => 'integer'
    ];

    /**
     * Поиск по клиенту (имя или телефон)
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('phone2', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Фильтр по дате создания от
     */
    public function scopeDateFrom(Builder $query, ?string $date): Builder
    {
        return $query->when($date, function($query, $date) {
            $query->whereDate('created_at', '>=', $date);
        });
    }

    /**
     * Фильтр по дате создания до
     */
    public function scopeDateTo(Builder $query, ?string $date): Builder
    {
        return $query->when($date, function($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        });
    }

    /**
     * Фильтр по статусу
     */
    public function scopeByStatus(Builder $query, ?int $status): Builder
    {
        return $query->when($status, function($query, $status) {
            $query->where('status', $status);
        });
    }

    public function getStatusNameAttribute(): string
    {
        return OfferStatusEnum::getName($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return OfferStatusEnum::getColor($this->status);
    }

    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class, 'offer_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
