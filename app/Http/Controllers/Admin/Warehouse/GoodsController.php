<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorySaveRequest;
use App\Http\Requests\GoodSaveRequest;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Good;
use Illuminate\Http\UploadedFile;

class GoodsController extends Controller
{
    public function index()
    {
        $goods = Good::query()
            ->byCategory(\request('category_id'))
            ->search(\request('search'))
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(30);
        $categories = Category::select('id', 'title')->get();

        return view('admin.goods.index', compact('goods', 'categories'));
    }

    public function categories()
    {
        return view('admin.goods.categories', [
            'categories' => Category::withCount('goods')->get()
        ]);
    }

    public function form(?Good $good)
    {
        $categories = Category::get();
        return view('admin.goods.form', compact('good', 'categories'));
    }

    public function save(GoodSaveRequest $request, ?Good $good)
    {
        $data = $request->validated();
        $data['status'] = !empty($data['status']);
        $data['category_id'] = $data['category_id'] ?? null;
        $data['image'] = $this->saveImage($request->file('image')) ?: $good->image;
        $good->fill($data);
        $good->save();

        return redirect()->route('admin.goods.form', ['good' => $good])
            ->with('success', 'Muvaffaqiyatli saqlandi');
    }

    public function saveCategory(CategorySaveRequest $request, ?Category $category)
    {
        $category->fill($request->validated());
        $category->save();
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }

    private function saveImage(?UploadedFile $image): ?string
    {
        if ($image) {
            $path = 'assets/images/goods';
            $filename = \Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($path, $filename);

            return $path . '/' . $filename;
        }

        return null;
    }
}
