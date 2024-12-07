<?php

namespace App\Models\Warehouse;

use App\Models\Client;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Balance extends Model
{
    protected $guarded = [];

    const TYPE_INCOME = 1; // when product accepted to warehouse
    const TYPE_EXPENSE = 4; // when product goes out from warehouse
    const TYPE_CANCEL_INCOME = 7;
    const TYPE_CANCEL_EXPENSE = 8;

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function clientImages(): HasMany
    {
        return $this->hasMany(Image::class, 'rel_id', 'id')
            ->where('rel_type', self::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->when(!empty($type), fn($query) => $query->where('type', $type));
    }

    public function scopeByGood($query, $good_id = null)
    {
        return $query->when(!empty($good_id), fn($query) => $query->where('good_id', $good_id));
    }

    public function scopeFilterByGood(Builder $query, ?array $filters = [])
    {
        $search = $filters['search'] ?? null;
        $category = $filters['category'] ?? null;

        $query->when($search || $category, function (Builder $query) use ($search, $category) {
            $query->whereHas('good', fn ($q) => $q->search($search)->byCategory($category));
        });
    }

    public function scopeSearchByClient(Builder $query, $search)
    {
        $query->when(
            !empty($search),
            fn($q) => $q->whereHas('client', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%') // поиск по имени клиента
                ->orWhere('phone', 'like', '%' . $search . '%')  // поиск по первому телефону
                ->orWhere('phone2', 'like', '%' . $search . '%'); // поиск по второму телефону
            })
        );
    }

    public function scopeByWarehouse($query, int|array|null $warehouse)
    {
        if (is_int($warehouse)) $warehouse = [$warehouse];

        return $query->when(!empty($warehouse), fn($query) => $query->whereIn('warehouse_id', $warehouse));
    }

    public function scopeFrom(Builder $query, ?string $from = null)
    {
        $query->when($from, fn($q) => $q->where('created_at', '>=', $from . ' 00:00:00'));
    }

    public function scopeTo(Builder $query, ?string $to = null)
    {
        $query->when($to, fn($q) => $q->where('created_at', '<=', $to . ' 23:59:59'));
    }

    public function scopeDoesntHavePrice(Builder $query)
    {
        $query->where(fn($q) => $q->whereNull('price_usd')->orWhere('price_usd', '<', 1));
    }

    public function getEditUrl(): string
    {
        return $this->type == self::TYPE_INCOME
            ? route('sklad.incomes.edit', ['balance' => $this->id])
            : route('sklad.expenses.edit', ['balance' => $this->id]);
    }
}
