<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\PrintSetting;
use App\Models\Services\Offer;
use Illuminate\Http\Request;
use App\Models\Services\ServiceCategory as Category;
use App\Http\Resources\Offers\OfferItemResource;
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
        $query = Offer::query()
            ->search($request->get('search'))
            ->dateFrom($request->get('date_from'))
            ->dateTo($request->get('date_to'));

        $offers = $query->byStatus($request->get('status'))->with(['manager'])->latest()->paginate(25)->withQueryString();
        $stats = $this->getOfferStats($query);

        return view('services.offers', compact('offers', 'stats'));
    }

    private function getOfferStats($query)
    {
        $query = $query->whereNot('status', OfferStatusEnum::Draft->value);
        $stats = [];
        if (auth()->user()->isAdmin()) {
            $stats['total'] = $query->count();
            $stats['total_discount'] = $query->sum('discount');
            $stats['total_cost'] = $query->sum('cost');
        }

        return $stats;
    }

    public function form()
    {
        $categories = Category::query()
            ->with(['services' => fn($q) => $q->where('active', true)->with('subServices')])
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
                    'redirect' => route('offers.index'),
                    'req_data' => $request->validated()
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
        $rules = [
            'status' => ['required', 'integer', Rule::in(array_keys(OfferStatusEnum::list()))]
        ];

        if (auth()->user()->isAdmin()) {
            $rules = array_merge($rules, [
                'client_name' => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'phone2' => ['nullable', 'string', 'max:255'],
                'created_at' => ['nullable', 'date']
            ]);
        }

        $validated = $request->validate($rules);
        if (auth()->user()->isAdmin()) {
            $validated['phone'] = $this->clearPhone($validated['phone']);
            $validated['phone2'] = $this->clearPhone($validated['phone2']);
        }
        $offer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Статус заказа обновлен'
        ]);
    }

    public function destroy(Offer $offer)
    {
        if ($offer->status !== OfferStatusEnum::Draft->value && !auth()->user()->isAdmin()) {
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
        // Загружаем связанные данные, включая подэлементы
        $offer->load(['offerItems.service', 'offerItems.subItems']);
        
        // Подготавливаем данные для формы
        $categories = Category::query()
            ->with(['services' => fn($q) => $q->where('active', true)->with('subServices')])
            ->whereHas('services')
            ->orderBy('id')
            ->get();
        
        $categories = CategoryWithServicesResource::collection($categories);
        
        // Подготовим начальные данные для Alpine.js
        $initialData = [
            'offerItems' => $offer->offerItems->whereNull('parent_item_id')->map(function($item) {
                $itemData = [
                    'inner_id' => mt_rand(100000, 999999), // Генерируем уникальный ID для Alpine.js
                    'id' => $item->service->id,
                    'name' => $item->service->name,
                    'unit' => $item->service->unit,
                    'image' => $item->service->image_url,
                    'full_price' => $item->service->full_price,
                    'custom_price' => $item->price != $item->original_price ? $item->price : null,
                    'isEditingPrice' => false,
                    'quantity' => $item->quantity,
                    'x' => $item->x,
                    'y' => $item->y,
                    'dimension_amount' => $item->dimension_amount,
                    'calculator' => $item->service->calculator_id,
                    'sub_services' => $item->service->subServices ?? []
                ];
                
                // Добавляем подэлементы, если они есть
                if ($item->subItems && $item->subItems->count() > 0) {
                    $itemData['subItems'] = $item->subItems->map(function($subItem) use ($itemData) {
                        return [
                            'inner_id' => mt_rand(100000, 999999), // Генерируем уникальный ID для Alpine.js
                            'id' => $subItem->service->id,
                            'name' => $subItem->service->name,
                            'unit' => $subItem->service->unit,
                            'image' => $subItem->service->image_url,
                            'full_price' => $subItem->service->full_price,
                            'price' => $subItem->price,
                            'custom_price' => $subItem->price != $subItem->original_price ? $subItem->price : null,
                            'isEditingPrice' => false,
                            'quantity' => $subItem->quantity,
                            'x' => $subItem->x,
                            'y' => $subItem->y,
                            'dimension_amount' => $subItem->dimension_amount,
                            'calculator' => $subItem->service->calculator_id,
                            'parent_inner_id' => $itemData['inner_id']
                        ];
                    })->toArray();
                } else {
                    $itemData['subItems'] = [];
                }
                
                return $itemData;
            })->toArray(),
            'discount' => $offer->discount,
            'client_name' => '', // Пустые поля для нового заказа
            'phone' => '',
            'phone2' => '',
            'comment' => '',
            'orderDate' => Carbon::now()->format('Y-m-d')
        ];

        return view('services.offer-copy-form', compact('categories', 'initialData'));
    }

    public function getPrint(Offer $offer)
    {
        $settings = PrintSetting::first() ?? new PrintSetting();
        $originalItems = $offer->offerItems;
       // Загружаем необходимые отношения
        $offer->load(['offerItems.service', 'offerItems.subItems', 'manager']);
        $items = $originalItems->whereNull('parent_item_id');

        // Преобразуем $offer в массив с помощью OfferResource
        $offer = (new OfferResource($offer))->toArray(request());
        $offer['offer_items'] = [];
        foreach($items as $item) {
            $offer['offer_items'][] = (new OfferItemResource($item))->toArray(request());
        }

        $groupedItems = $originalItems->groupBy('service_id');
        // Создаем массив $totals
        $totals = [];

        foreach ($groupedItems as $serviceId => $group) {
            if ($group->sum('quantity') == 1 && count($group) < 2) {
                continue;
            }
            // Получаем имя услуги и единицу измерения из первой позиции группы
            $serviceName = $group->first()->service->name;
            $unit = $group->first()->service->unit;

            // Вычисляем сумму dimension_amount для группы
            $totalDimensionAmount = 0; 
            foreach($group as $g) {
                $totalDimensionAmount += ($g->dimension_amount * $g->quantity);
            }

            // Добавляем результат в массив $totals
            $totals[] = "$serviceName " . number_format($totalDimensionAmount, 2, '.', ' ') . " $unit";
        }
        return view('prints.offer', compact('offer', 'settings', 'totals'));
    }

    private function clearPhone($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);
            return $phone ?: null;
        }
        return null;
    }
}
