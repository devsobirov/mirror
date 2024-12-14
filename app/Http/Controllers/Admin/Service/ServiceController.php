<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function categories()
    {
        $items = ServiceCategory::orderBy('id', 'desc')
            ->withCount('services')
            ->get();

        return view('admin.services.categories', compact('items'));
    }

    public function categorySave(Request $request, ?ServiceCategory $serviceCategory)
    {
        $data = $request->validate([
            'title' => 'required|min:3|unique:service_categories,title,'. $serviceCategory?->id,
        ], [
            'title.unique' => 'Ushbu nom boshqa xizmat turi uchun kiritilgan, boshqani kiriting'
        ]);
        $serviceCategory->fill($data);
        $serviceCategory->save();
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }

    public function services()
    {

    }

    public function serviceForm(?Service $service)
    {

    }

    public function serviceSave(Request $request, ?Service $service)
    {

    }
}
