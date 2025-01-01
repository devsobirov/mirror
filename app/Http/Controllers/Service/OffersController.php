<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Services\Offer;
use Illuminate\Http\Request;

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
        //
    }
}
