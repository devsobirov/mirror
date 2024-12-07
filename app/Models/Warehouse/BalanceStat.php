<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BalanceStat extends Model
{
    public static function boot()
    {
        parent::boot();

        static::saving(function (BalanceStat $balanceStat) {
            $balanceStat->updated_at = now();
        });
    }

    public $table = 'balance_stats';
    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [
        'updated_at' => 'datetime',
        'last_income_date' => 'datetime',
        'last_expense_date' => 'datetime',
    ];

    public function scopeByGood(Builder $query, null|int|array $good_ids)
    {
        if (is_int($good_ids)) {
            $good_ids = [$good_ids];
        }
        $query->when(!empty($good_ids), fn($query) => $query->where('good_id', $good_ids));
    }

    public function scopeByWarehouse(Builder $query, null|array|int $warehouseIds = null)
    {
        if (is_int($warehouseIds)) {
            $warehouseIds = [$warehouseIds];
        }
        $query->when(!empty($warehouseIds),  fn($q) => $q->whereIn('warehouse_id', $warehouseIds));
    }
}
