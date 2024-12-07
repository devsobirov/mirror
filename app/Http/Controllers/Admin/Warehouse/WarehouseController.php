<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseSaveRequest;
use App\Models\Warehouse\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('employees')->get();
        return view('admin.warehouse.index', compact('warehouses'));
    }

    public function save(WarehouseSaveRequest $request, Warehouse $warehouse)
    {
        $warehouse->fill($request->validated());
        $warehouse->save();

        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }
}
