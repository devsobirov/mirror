<?php

namespace App\Http\Controllers\Warehouse;

use App\Exports\IncomeReportExport;
use App\Http\Requests\IncomeUpdateRequest;
use App\Models\Warehouse\Balance;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Good;
use App\Models\Warehouse\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends BaseWarehouseController
{
    public function index()
    {
        $items = Balance::query()
            ->byType($this->action_type)
            ->byWarehouse(request('warehouse_id') ?: $this->allowed_warehouse_ids)
            ->filterByGood(request()->only('search', 'category_id'))
            ->from(request('from'))
            ->to(request('to'))
            ->with(
                ['good' => fn($query) => $query->select('id', 'name', 'category_id', 'image')->with('category')],
                'supplier:id,name',
                'user:id,name',
                'warehouse:id,name',
            )
            ->orderBy('id', 'desc')
            ->paginate(30)
            ->withQueryString();
        $categories = Category::select('id', 'title')->get();
        $suppliers = Supplier::select('id', 'name')->get();
        $warehouses = $this->allowed_warehouses;

        return view('sklad.income', compact('items', 'warehouses', 'suppliers', 'categories'));
    }

    public function income(Request $request)
    {
        $extend = $request->validate([
            'supplier_id' => 'nullable|integer',
        ]);
        $this->saveBalance($request, $extend);
        $this->updateStat($request->good_id, $request->warehouse_id, $request->amount);
        return redirect()->back()->with('success', 'Muvaffqiyatli saqlandi');
    }

    public function edit(Balance $balance)
    {
        if (!auth()->user()->isAdmin() || $balance->type !== $this->action_type) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $suppliers = Supplier::select('id', 'name')->get();
        $warehouses = $this->allowed_warehouses;
        return view('sklad.income-edit', compact('balance', 'suppliers', 'warehouses'));
    }

    public function update(IncomeUpdateRequest $request, Balance $balance)
    {
        if (!auth()->user()->isAdmin() || $balance->type !== $this->action_type) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $data = $request->except('_token', '_method');
        $originalAmount = $balance->amount;
        $originalWarehouse = $balance->warehouse_id;
        $diff = $originalAmount - $data['amount'];
        if ($data['created_at'] === $balance->created_at?->format('Y-m-d')) {
            $data['created_at'] = $balance->created_at->format('Y-m-d');
        } else {
            $data['created_at'] = $data['created_at'] . ' '. date('H:i:s');
        }

        $balance->fill($data);
        $balance->save();

        if ($originalWarehouse !== $data['warehouse_id']) {
            $this->updateStat($balance->good_id, $data['warehouse_id'], $balance->amount);
            $this->action_type = Balance::TYPE_CANCEL_INCOME;
            $this->updateStat($balance->good_id, $originalWarehouse, $originalAmount);
        } elseif ($diff) {
            $this->updateStat($balance->good_id, $originalWarehouse, $diff);
        }

        return redirect()->back()->with('success', 'Muvaffaqiyatli yangilandi');
    }

    public function delete(Balance $balance)
    {
        $this->action_type = Balance::TYPE_CANCEL_INCOME;
        $this->updateStat($balance->good_id, $balance->warehouse_id, $balance->amount);
        $balance->delete();

        return redirect()->route('sklad.incomes.index')->with('success', 'Muvaffaqiyatli o\'chirildi');
    }

    public function export(Request $request, ?Good $good)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $filename = \Str::slug($request->input('filename')) . '.xlsx';
        $items = Balance::query()
            ->byType($this->action_type)
            ->byGood($good?->id)
            ->byWarehouse($request->export_warehouse_id)
            ->filterByGood(['category_id' => $request->export_category_id])
            ->from($request->export_from)
            ->to($request->export_to)
            ->with(
                ['good' => fn($query) => $query->select('id', 'name', 'category_id')->with('category')],
                'supplier:id,name',
                'user:id,name',
                'warehouse:id,name',
            )
            ->orderBy('id', 'desc')
            ->get();

        return Excel::download(new IncomeReportExport($items), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    protected function setActionType(): void
    {
        $this->action_type = Balance::TYPE_INCOME;
    }
}
