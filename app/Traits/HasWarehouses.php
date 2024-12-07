<?php

namespace App\Traits;

use App\Models\Warehouse\Warehouse;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasWarehouses
{
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(
            Warehouse::class ,
            'warehouse_users',
            'user_id',
            'warehouse_id',
            'id',
            'id'
        )->withPivot('action');
    }

    public function hasIncomeActionWarehouses(): bool
    {
        return $this->warehouses->where('active', true)->where('pivot.action', Warehouse::ACTION_IN)->count() > 0;
    }

    public function hasExpenseActionWarehouses(): bool
    {
        return $this->warehouses->where('active', true)->where('pivot.action', Warehouse::ACTION_OUT)->count() > 0;
    }
}
