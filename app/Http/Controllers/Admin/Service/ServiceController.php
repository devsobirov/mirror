<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CategorySaveRequest;
use App\Http\Requests\Service\ServiceSaveRequest;
use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ServiceController extends Controller
{
    public function categories()
    {
        $items = ServiceCategory::orderBy('id', 'desc')
            ->withCount('services')
            ->get();

        return view('admin.services.categories', compact('items'));
    }

    public function categorySave(CategorySaveRequest $request, ?ServiceCategory $serviceCategory)
    {
        $serviceCategory->fill($request->validated());
        $serviceCategory->save();
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }

    public function services()
    {
        $items = Service::query()
            ->byCategory(\request('category_id'))
            ->search(\request('search'))
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(30);

        $categories = ServiceCategory::select('id', 'title')->get();
        return view('admin.services.index', compact('items', 'categories'));
    }

    public function serviceForm(?Service $service)
    {
        $categories = ServiceCategory::select('id', 'title')->get();
        return view('admin.services.form', compact('service', 'categories'));
    }

    public function serviceSave(ServiceSaveRequest $request, ?Service $service)
    {
        $data = $request->validated();
        $data['image'] = $this->saveImage($request->file('image')) ?? $service->image;
        $data['active'] = !empty($data['active']);
        $service->fill($data);
        $service->save();
        return redirect()->route('admin.services.form', ['service' => $service->id])
            ->with('success', 'Muvaffaqiyatli saqlandi');
    }

    private function saveImage(?UploadedFile $image): ?string
    {
        if ($image) {
            $path = Service::IMAGE_PATH;
            $filename = \Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($path, $filename);

            return $path . '/' . $filename;
        }

        return null;
    }
}
