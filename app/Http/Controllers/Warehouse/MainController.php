<?php

namespace App\Http\Controllers\Warehouse;

use App\Exports\BalanceReportExport;
use App\Models\Warehouse\Balance;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Good;
use App\Models\Warehouse\Supplier;
use App\Models\Warehouse\Warehouse;
use Maatwebsite\Excel\Facades\Excel;

class MainController extends BaseWarehouseController
{
    public function index()
    {
        $goods = Good::query()
            ->select('id','name', 'price', 'category_id', 'image')
            ->search(request('search'))
            ->byCategory(request('category_id'))
            ->with([
                'balanceStat' => fn($q) => $q->byWarehouse($this->allowed_warehouse_ids),
                'category'
            ])
            ->withMax('balance as last_update', 'created_at')
            ->orderByDesc('last_update')
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::select('id','name')->get();
        $categories = Category::select('id','title')->get();
        return view('sklad.index', [
            'goods' => $goods,
            'warehouses' => $this->allowed_warehouses,
            'suppliers' => $suppliers,
            'categories' => $categories
        ]);
    }

    public function good(Good $good)
    {
        $warehouses = $this->allowed_warehouses;
        $balanceStat = $good->balanceStat()
            ->whereIn('warehouse_id', $this->allowed_warehouse_ids)
            ->selectRaw('SUM(amount) as amount, year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        $fullStat = Balance::query()
            ->whereIn('warehouse_id', $this->allowed_warehouse_ids)
            ->where('good_id', $good->id)
            ->selectRaw('
                YEAR(created_at) as year,
                SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as income_sum,
                SUM(CASE WHEN type = ? THEN price_usd ELSE 0 END) as income_price,
                SUM(CASE WHEN type = ? AND amount > 0 AND price_usd > 0 THEN amount ELSE 0 END) as income_price_amount,
                SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as expense_sum,
                SUM(CASE WHEN type = ? THEN price_usd ELSE 0 END) as expense_price,
                SUM(CASE WHEN type = ? AND amount > 0 AND price_usd > 0 THEN amount ELSE 0 END) as expense_price_amount
        ', [
                Balance::TYPE_INCOME,
                Balance::TYPE_INCOME,
                Balance::TYPE_INCOME,
                Balance::TYPE_EXPENSE,
                Balance::TYPE_EXPENSE,
                Balance::TYPE_EXPENSE,
            ])
            ->orderByDesc('year')
            ->groupBy('year')
            ->get();

        return view(
            'sklad.good', compact(
                'good',
                'warehouses',
                'balanceStat',
                'fullStat'
            )
        );
    }

    public function export()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $filename = \Str::slug(\request()->input('filename')) . '.xlsx';
        $items = Good::query()
            ->select('id','name', 'price', 'category_id')
            ->byCategory(request('export_category_id'))
            ->with([
                'balanceStat' => fn($q) => $q->byWarehouse(\request('warehouse_id')),
                'category'
            ])
            ->withMax('balance as last_update', 'created_at')
            ->orderByDesc('last_update')
            ->get();
        $warehouses = Warehouse::where('active', true)->get();
        $warehouse = request()->input('export_warehouse_id')
            ? $warehouses->where('id', request()->input('export_warehouse_id'))->first()
            : null;
        return Excel::download(new BalanceReportExport($items, $warehouses, $warehouse), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    protected function setActionType()
    {
        //
    }
}
