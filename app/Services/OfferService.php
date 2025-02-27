<?php

namespace App\Services;

use App\Models\Services\Offer;
use App\Enums\Offer\OfferStatusEnum;

class OfferService
{
    public function save(array $data): Offer
    {
        // Добавляем ID менеджера к данным
        $data['manager_id'] = auth()->id();
        
        // Подготавливаем данные заказа
        $offerData = [
            'client_name' => $data['client_name'],
            'phone' => $data['phone'],
            'phone2' => $data['phone2'],
            'comment' => $data['comment'],
            'status' => $data['status'] ?? OfferStatusEnum::Draft->value,
            'discount' => $data['discount'] ?? 0,
            'manager_id' => $data['manager_id'],
        ];

        if ($date = $data['created_at'] ?? null) {
            $offerData['created_at'] = $date;
        }
        
        // Рассчитываем общую стоимость и скидку
        $totalCost = collect($data['offer_items'])->sum(function ($item) {
            return $item['cost'];
        });

        // Добавляем рассчитанные поля
        $offerData['cost'] = $totalCost - $data['discount'];

        // Создаем заказ
        $offer = Offer::create($offerData);

        // Создаем позиции заказа
        foreach ($data['offer_items'] as $item) {
            $offerItem = $offer->offerItems()->create([
                'service_id' => $item['service_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'original_price' => $item['original_price'],
                'cost' => $item['cost'],
                'item_cost' => $item['item_cost'],
                'x' => $item['x'] ?? null,
                'y' => $item['y'] ?? null,
                'dimension_amount' => $item['dimension_amount'] ?? null
            ]);

            // Создаем подпозиции, если они есть
            if (!empty($item['sub_items'])) {
                foreach ($item['sub_items'] as $subItem) {
                    $offerItem->subItems()->create([
                        'service_id' => $subItem['service_id'],
                        'quantity' => $subItem['quantity'],
                        'price' => $subItem['price'],
                        'original_price' => $subItem['original_price'],
                        'cost' => $subItem['cost'],
                        'item_cost' => $subItem['item_cost'],
                        'x' => $subItem['x'] ?? null,
                        'y' => $subItem['y'] ?? null,
                        'dimension_amount' => $subItem['dimension_amount'] ?? null,
                        'parent_item_id' => $offerItem->id,
                        'offer_id' => $offer->id,
                    ]);
                }
            }
        }

        return $offer->load(['offerItems', 'offerItems.subItems']);
    }
}