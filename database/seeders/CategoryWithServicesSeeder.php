<?php

namespace Database\Seeders;

use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use Illuminate\Database\Seeder;

class CategoryWithServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Диагностика',
                'services' => [
                    ['name' => 'Компьютерная диагностика', 'price' => 15000, 'unit' => 'точка'],
                    ['name' => 'Диагностика ходовой части', 'price' => 10000, 'unit' => 'осмотр'],
                    ['name' => 'Диагностика двигателя', 'price' => 20000, 'unit' => 'осмотр'],
                    ['name' => 'Диагностика электрики', 'price' => 12000, 'unit' => 'точка'],
                ]
            ],
            [
                'title' => 'Замена масла',
                'services' => [
                    ['name' => 'Замена масла в двигателе', 'price' => 5000, 'unit' => 'замена'],
                    ['name' => 'Замена масла в АКПП', 'price' => 25000, 'unit' => 'замена'],
                    ['name' => 'Замена масла в МКПП', 'price' => 15000, 'unit' => 'замена'],
                    ['name' => 'Замена масла в раздатке', 'price' => 8000, 'unit' => 'замена'],
                ]
            ],
            [
                'title' => 'Ремонт двигателя',
                'services' => [
                    ['name' => 'Замена прокладки ГБЦ', 'price' => 45000, 'unit' => 'шт'],
                    ['name' => 'Замена ремня ГРМ', 'price' => 35000, 'unit' => 'комплект'],
                    ['name' => 'Замена цепи ГРМ', 'price' => 50000, 'unit' => 'комплект'],
                    ['name' => 'Замена сальников', 'price' => 15000, 'unit' => 'шт'],
                    ['name' => 'Регулировка клапанов', 'price' => 20000, 'unit' => 'комплект'],
                ]
            ],
            [
                'title' => 'Ремонт ходовой',
                'services' => [
                    ['name' => 'Замена амортизаторов', 'price' => 10000, 'unit' => 'шт'],
                    ['name' => 'Замена пружин', 'price' => 8000, 'unit' => 'шт'],
                    ['name' => 'Замена шаровых опор', 'price' => 5000, 'unit' => 'шт'],
                    ['name' => 'Замена рычагов', 'price' => 12000, 'unit' => 'шт'],
                ]
            ],
            [
                'title' => 'Шиномонтаж',
                'services' => [
                    ['name' => 'Замена колес', 'price' => 2000, 'unit' => 'колесо'],
                    ['name' => 'Балансировка', 'price' => 1500, 'unit' => 'колесо'],
                    ['name' => 'Ремонт проколов', 'price' => 3000, 'unit' => 'прокол'],
                    ['name' => 'Правка дисков', 'price' => 5000, 'unit' => 'диск'],
                ]
            ],
            [
                'title' => 'Кузовной ремонт',
                'services' => [
                    ['name' => 'Покраска элемента', 'price' => 35000, 'unit' => 'элемент'],
                    ['name' => 'Рихтовка', 'price' => 15000, 'unit' => 'элемент'],
                    ['name' => 'Полировка', 'price' => 20000, 'unit' => 'элемент'],
                    ['name' => 'Антикоррозийная обработка', 'price' => 40000, 'unit' => 'машина'],
                ]
            ],
            [
                'title' => 'Электрика',
                'services' => [
                    ['name' => 'Замена аккумулятора', 'price' => 3000, 'unit' => 'шт'],
                    ['name' => 'Ремонт стартера', 'price' => 15000, 'unit' => 'шт'],
                    ['name' => 'Ремонт генератора', 'price' => 18000, 'unit' => 'шт'],
                    ['name' => 'Диагностика электропроводки', 'price' => 10000, 'unit' => 'точка'],
                ]
            ],
            [
                'title' => 'Дополнительные услуги',
                'services' => [
                    ['name' => 'Химчистка салона', 'price' => 25000, 'unit' => 'салон'],
                    ['name' => 'Заправка кондиционера', 'price' => 12000, 'unit' => 'заправка'],
                    ['name' => 'Регулировка фар', 'price' => 5000, 'unit' => 'пара'],
                    ['name' => 'Замена лобового стекла', 'price' => 35000, 'unit' => 'шт'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = ServiceCategory::create([
                'title' => $categoryData['title'],
            ]);

            foreach ($categoryData['services'] as $serviceData) {
                Service::create([
                    'category_id' => $category->id,
                    'name' => $serviceData['name'],
                    'price' => $serviceData['price'],
                    'unit' => $serviceData['unit'],
                    'active' => true,
                    'margin' => 0
                ]);
            }
        }
    }
}
