<?php

namespace App\Services;

use App\Models\Services\Offer;
use App\Enums\Offer\OfferStatusEnum;

class OfferService
{
    public function save(array $data): Offer
    {
        // Подготавливаем данные заказа
        $offerData = [
            'client_name' => $data['client_name'],
            'phone' => $data['phone'],
            'phone2' => $data['phone2'],
            'comment' => $data['comment'],
            'status' => $data['status'] ?? OfferStatusEnum::Draft->value,
            'discount' => $data['discount'],
        ];

        // Рассчитываем общую стоимость и скидку
        $totalCost = collect($data['offer_items'])->sum(function ($item) {
            return $item['cost'];
        });

        // Добавляем рассчитанные поля
        $offerData['discount'] = $data['discount'];
        $offerData['cost'] = $totalCost - $data['discount'];

        // Создаем заказ
        $offer = Offer::create($offerData);

        // Создаем позиции заказа
        $offer->offerItems()->createMany($data['offer_items']);

        return $offer->load('offerItems');
    }
} 