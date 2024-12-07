<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierSaveRequest;
use App\Models\Warehouse\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.suppliers.index', [
            'suppliers' => Supplier::all()
        ]);
    }

    public function save(SupplierSaveRequest $request, ?Supplier $supplier)
    {
        $supplier->fill($request->validated());
        $supplier->save();
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }
}
