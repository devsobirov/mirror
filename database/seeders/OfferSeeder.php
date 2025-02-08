<?php

namespace Database\Seeders;

use App\Models\Services\Offer;
use App\Models\Services\OfferItem;
use App\Models\Services\Service;
use App\Models\User;
use App\Enums\Offer\OfferStatusEnum;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем все услуги и менеджеров заранее
        $services = Service::all();
        $managers = User::all();

        // Создаем 2000 заказов
        for ($i = 0; $i < 2000; $i++) {
            // Базовые данные для заказа
            $phone = fake()->numerify('#########'); // 9 цифр для номера
            $phone2 = fake()->boolean(40) ? fake()->numerify('#########') : null;

            $offer = Offer::create([
                'client_name' => fake()->name(),
                'phone' => $phone,
                'phone2' => $phone2,
                'comment' => fake()->boolean(70) ? fake()->sentence() : null,
                'status' => fake()->randomElement(array_keys(OfferStatusEnum::list())),
                'manager_id' => $managers->random()->id,
                'created_at' => fake()->dateTimeBetween('2024-10-01', 'now'),
            ]);

            // Создаем от 1 до 5 позиций для заказа
            $itemsCount = rand(1, 5);
            $totalCost = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $service = $services->random();
                $quantity = fake()->randomElement([
                    1, 1, 1, 1, // чаще целые числа
                    fake()->randomFloat(3, 0.1, 5) // реже дробные
                ]);
                
                $hasDiscount = fake()->boolean(20);
                $price = $hasDiscount ? 
                    round($service->price * (1 - fake()->randomFloat(2, 0.05, 0.3))) : 
                    $service->price;
                
                $itemCost = round($price * $quantity, 2);
                $totalCost += $itemCost;

                OfferItem::create([
                    'offer_id' => $offer->id,
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'original_price' => $service->price,
                    'cost' => $itemCost
                ]);
            }

            // Применяем скидку к заказу (в 30% случаев)
            $discount = 0;
            if (fake()->boolean(30)) {
                $discount = round($totalCost * fake()->randomFloat(2, 0.05, 0.25));
                $totalCost -= $discount;
            }

            // Обновляем итоговые суммы заказа
            $offer->update([
                'cost' => $totalCost,
                'discount' => $discount
            ]);
        }
    }
} 