<?php

namespace Database\Seeders;

use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Oyna sexi',
                'services' => [
                    ['name' => 'Prostoy oyna', 'price' => 50_000, 'unit' => 'm2', 'margin' => 10],
                    ['name' => 'Zolotistka oyna', 'price' => 180_000, 'unit' => 'm2', 'margin' => 10],
                    ['name' => 'Yudiy oyna', 'price' => 110_000, 'unit' => 'm2', 'margin' => 10],
                    ['name' => 'Pepelniy oyna', 'price' => 170_000, 'unit' => 'm2', 'margin' => 10],
                    ['name' => 'Obichniy oyna', 'price' => 50_000, 'unit' => 'm2', 'margin' => 10],
                ]
            ],
            [
                'title' => 'Rombik',
                'services' => [
                    ['name' => 'Zerkalo Rombik', 'price' => 350_000, 'unit' => 'm2',],
                    ['name' => 'Zolotistka Rombik', 'price' => 500_000, 'unit' => 'm2',],
                    ['name' => 'Yudiy Rombik', 'price' => 400_000, 'unit' => 'm2',],
                    ['name' => 'Rombik Ustanovka', 'price' => 80_000, 'unit' => 'dona',],
                    ['name' => 'Rombik Kley', 'price' => 10_000, 'unit' => 'dona',],
                ]
            ],
            [
                'title' => 'Lazer',
                'services' => [
                    ['name' => 'List - 1', 'price' => 350_000, 'unit' => 'dona'],
                    ['name' => 'List - 2', 'price' => 0, 'unit' => 'dona', 'active' => false],
                    ['name' => 'Foton - 1', 'price' => 120_000, 'unit' => 'm2',],
                    ['name' => 'Foton - 2', 'price' => 300_000, 'unit' => 'm2',],
                ]
            ],
            [
                'title' => 'Pux',
                'services' => [
                    ['name' => '04-19', 'price' => 2500, 'unit' => 'm'],
                    ['name' => '01-19', 'price' => 3500, 'unit' => 'm'],
                    ['name' => '01-21', 'price' => 4000, 'unit' => 'm'],
                    ['name' => '02-21', 'price' => 5500, 'unit' => 'm'],
                    ['name' => '01-35', 'price' => 6500, 'unit' => 'm'],
                    ['name' => '02-35', 'price' => 9000, 'unit' => 'm'],
                ]
            ],
            [
                'title' => 'Farset',
                'services' => [
                    ['name' => 'Farset', 'price' => 3500, 'unit' => 'm'],
                ]
            ],
            [
                'title' => 'Rover',
                'services' => [
                    ['name' => 'Rover List', 'price' => 400_000, 'unit' => 'dona'],
                    ['name' => 'Rover o\'yish', 'price' => 90_000, 'unit' => 'dona'],
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
                    'active' => $serviceData['active'] ?? true,
                    'margin' => $serviceData['margin'] ?? 0
                ]);
            }
        }
    }
}
