<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Services\Offer;
use Illuminate\Http\Request;
use App\Models\Services\ServiceCategory as Category;
use App\Http\Resources\Services\CategoryWithServicesResource;

class OffersController extends Controller
{
    public function index()
    {
        $offers = Offer::query()
            ->with('offerItems')
            ->orderByDesc('id')
            ->paginate(15);

        dd($offers);
    }

    public function form()
    {
        $categories = Category::query()
            ->with(['services' => fn($q) => $q->where('active', true)])
            ->whereHas('services')
            ->get();

        $categories = CategoryWithServicesResource::collection($categories);
        return view('services.offer-form', compact('categories'));
    }
}
