<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Services\Offer;
use Illuminate\Http\Request;
use App\Models\Services\ServiceCategory as Category;
use App\Http\Resources\Services\CategoryWithServicesResource;
use App\Http\Requests\Offers\SaveOfferRequest;
use App\Http\Resources\Offers\OfferResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Enums\Offer\OfferStatusEnum;
use Illuminate\Validation\Rule;
use App\Services\OfferService;
use Carbon\Carbon;

class OffersController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(Request $request)
    {
        $offers = Offer::query()
            ->with(['manager'])
            ->search($request->get('search'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'))
            ->byStatus($request->get('status'))
            ->latest()
            ->paginate(25);

        return view('services.offers', compact('offers'));
    }

    public function form()
    {
        $categories = Category::query()
            ->with(['services' => fn($q) => $q->where('active', true)])
            ->whereHas('services')
            ->orderBy('id')
            ->get();

        $categories = CategoryWithServicesResource::collection($categories);
        return view('services.offer-form', compact('categories'));
    }

    public function save(SaveOfferRequest $request)
    {
        try {
            $offer = $this->offerService->save($request->validated());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Заказ успешно сохранен',
                    'redirect' => route('offers.index')
                ]);
            }
            
            return redirect()->route('offers.index')->with('success', 'Заказ успешно сохранен');
        } catch (\Exception $e) {
            \Log::error('Error saving offer:', ['error' => $e->getMessage()]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при сохранении заказа'
                ], 500);
            }
            
            return back()->with('error', 'Ошибка при сохранении заказа');
        }
    }

    public function updateStatus(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', Rule::in(array_keys(OfferStatusEnum::list()))]
        ]);

        $offer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Статус заказа обновлен'
        ]);
    }

    public function destroy(Offer $offer)
    {
        if ($offer->status !== OfferStatusEnum::Draft->value) {
            return response()->json([
                'success' => false,
                'message' => 'Можно удалять только черновики заказов'
            ], 403);
        }

        $offer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Заказ удален'
        ]);
    }

    public function show(Offer $offer)
    {
        // Явно загружаем связанные данные только для детального просмотра
        $offer->load(['offerItems.service', 'manager']);
        
        return new OfferResource($offer);
    }

    public function copy(Offer $offer)
    {
        $offer->load(['offerItems.service']);
        
        // Подготавливаем данные для формы
        $categories = CategoryWithServicesResource::collection(Category::with(['services'])->get());
        
        // Подготовим начальные данные для Alpine.js
        $initialData = [
            'offerItems' => $offer->offerItems->map(function($item) {
                return [
                    'id' => $item->service->id,
                    'name' => $item->service->name,
                    'unit' => $item->service->unit,
                    'image' => $item->service->image_url,
                    'full_price' => $item->service->full_price,
                    'custom_price' => $item->price != $item->original_price ? $item->price : null,
                    'isEditingPrice' => false,
                    'quantity' => $item->quantity
                ];
            }),
            'discount' => $offer->discount
        ];

        return view('services.offer-copy-form', compact('categories', 'initialData'));
    }
}
