<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse\Balance;
use App\Models\Warehouse\BalanceStat;
use App\Models\Warehouse\Warehouse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

abstract class BaseWarehouseController extends Controller
{
    protected Authenticatable|User $user;
    protected ?int $action_type = null;
    protected array $allowed_warehouse_ids = [];
    protected array $allowed_warehouses = [];

    public function __construct()
    {
        $this->setActionType();
        $this->setUserData();
    }

    protected function setUserData(): void
    {
        $this->middleware(function ($request, $next) {
            abort_if(
                !auth()->user() || !auth()->user()->isWorker(),
                403,
                'Oh, wrong door, how did you get here? =)'
            );
           $this->user = auth()->user();
           $this->setWarehouses();
           return $next($request);
        });
    }

    protected function setWarehouses(): void
    {
        $warehouses = Warehouse::getWarehousesForUser(
            !$this->user->isAdmin() ? $this->user->id : null,
            !$this->user->isAdmin() ? $this->getAlternativeActionType() : null
        );

        $this->allowed_warehouses = $warehouses->pluck('name', 'id')->toArray();
        $this->allowed_warehouse_ids = $warehouses->pluck('id')->toArray();
    }

    protected function saveBalance(Request $request, array $extend = []): Balance
    {
        $data = $request->validate([
            'good_id' => 'required|integer|exists:goods,id',
            'amount' => 'required|integer|min:1',
            'price_usd' => 'nullable|integer|min:0',
            'created_at' => 'required|date',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'description' => 'nullable|string'
        ]);

        $data['price_usd'] = $data['price_usd'] ?? 0;
        $data['created_at'] .= (' ' . now()->format('H:i:s'));
        $data['type'] = $this->action_type;
        $data['user_id'] = \Auth::id();
        $data = array_merge($data, $extend);

        return Balance::create($data);
    }

    protected function updateStat(int $good_id, int $warehouse_id, int $actionAmount): bool
    {
        $stat = BalanceStat::query()
            ->where('good_id', $good_id)
            ->where('warehouse_id', $warehouse_id)
            ->where('year', date('Y'))
            ->first() ?? new BalanceStat();

        $amount = $this->getNewAmount($stat, $actionAmount);
        $timestamp = $this->getStatUpdateTimestampField();

        $stat->fill([
            'amount' => $amount,
            'good_id' => $good_id,
            'warehouse_id' => $warehouse_id,
            'year' => date('Y'),
            $timestamp => now(),
        ]);

        return $stat->save();
    }

    protected abstract function setActionType();

    private function getStatUpdateTimestampField(): string
    {
        return match ((int)$this->action_type) {
            Balance::TYPE_INCOME => 'last_income_date',
            Balance::TYPE_CANCEL_EXPENSE => 'last_expense_date',
            default => 'updated_at'
        };
    }

    private function getNewAmount(BalanceStat $stat, int $amount): int
    {
        $current = $stat->amount ?? 0;

        return match ((int)$this->action_type) {
            Balance::TYPE_INCOME, Balance::TYPE_CANCEL_EXPENSE => $current + $amount,
            Balance::TYPE_EXPENSE, Balance::TYPE_CANCEL_INCOME  => $current - $amount,
            default => $amount,
        };
    }

    private function getAlternativeActionType(): ?int
    {
        return match ((int)$this->action_type) {
            Balance::TYPE_INCOME => Warehouse::ACTION_IN,
            Balance::TYPE_EXPENSE => Warehouse::ACTION_OUT,
            default => null
        };
    }
}
