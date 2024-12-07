<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\Balance;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->isAdmin()
            ? $this->adminDashboard()
            : view('home');
    }

    private function adminDashboard()
    {
        $report = Balance::query()
            ->whereBetween('created_at', [
                now()->subDay()->startOfDay(),
                now()->endOfDay()
            ])
            ->with(
                ['good' => fn($query) => $query->select('id', 'name', 'category_id', 'image')->with('category')],
                'user:id,name',
                'warehouse:id,name',
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        $withoutPrice = Balance::doesntHavePrice()
            ->with(['good' => fn($query) => $query->select('id', 'name', 'category_id', 'image')->with('category')])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.home', compact('report', 'withoutPrice'));
    }
}
