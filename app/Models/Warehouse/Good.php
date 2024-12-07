<?php

namespace App\Models\Warehouse;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Good extends Model
{
    use HasFactory;

    public $table = 'goods';
    public const IMAGE_PATH = 'assets/images/goods';
    protected $guarded = false;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function balanceStat(): HasMany
    {
        return $this->hasMany(BalanceStat::class, 'good_id', 'id')
            ->where('year', date('Y'));
    }

    public function balance(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Balance::class)->where('type', Balance::TYPE_INCOME);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Balance::class)->where('type', Balance::TYPE_EXPENSE);
    }

    public function getFullTitleAttribute(): string
    {
        return ($this->category?->title ?? 'Xar-xil') . ' / ' . $this->name;
    }

    public function getLastIncomeAttribute($value): ?string
    {
        if (!empty($value)) {
            return Carbon::createFromDate($value)->format('d-M Y');
        }

        return null;
    }

    public function getCurrentAmountAttribute(): int
    {
        return $this->balanceStat->sum('amount');
    }

    public function scopeSearch(Builder $query, ?string $search = '')
    {
        $query->when(!empty($search), fn($q) => $q->where('name', 'like', '%' . mb_strtolower(trim($search)) . '%'));
    }

    public function scopeByCategory(Builder $query, ?int $category_id = null)
    {
        $query->when(!empty($category_id), fn($q) => $q->where('category_id', $category_id));
    }

    public function isInStock($warehouseIds): bool
    {
        return $this->balanceStat->whereIn('warehouse_id', $warehouseIds)
            ->where('amount', '>', 0)
            ->count() > 0;
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: 'assets/images/no-image.jpg');
    }
}
