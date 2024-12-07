<?php

namespace App\Models\Warehouse;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    public $table = 'warehouses';
    public $timestamps = false;
    protected $guarded = false;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    // тип действия для доступов
    const ACTION_IN = 1;
    const ACTION_OUT = 2;

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class ,
            'warehouse_users',
            'warehouse_id',
            'user_id',
            'id',
            'id'
        )->withPivot('action');
    }

    public static function getWarehousesForUser($user_id = null, $action = null): Collection
    {
        return self::query()
            ->byUserAndAction($user_id, $action)
            ->where('active', self::STATUS_ACTIVE)
            ->select('id', 'name')
            ->get();
    }

    public function scopeByUserAndAction(Builder $query,?int $user_id = null, ?int $action = null)
    {
        return $query->when(
            $user_id,
            fn(Builder $query) => $query->whereHas(
                'employees',
                fn(Builder $sub) =>
                    $sub->where('user_id', $user_id)
                    ->when($action, fn($sub) => $sub->where('warehouse_users.action', $action))
            )
        );
    }
}
