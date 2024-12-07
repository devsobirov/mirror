<?php

namespace Database\Seeders;

use App\Models\Warehouse\Category;
use App\Models\Warehouse\Good;
use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    private $categories = [
        ['id' => 1, 'title' => 'Panel'],
        ['id' => 2, 'title' => 'Invertor'],
        ['id' => 3, 'title' => 'Nasos'],
        ['id' => 4, 'title' => 'Akkumulyator (Gel.)'],
        ['id' => 5, 'title' => 'Akkumulyator (Lion.)'],
    ];

    private $products = [
        ['category_id' => 1, 'name' => 'YUVONchan 550 Vt'],
        ['category_id' => 1, 'name' => 'YUVON 2 550 Vt'],
        ['category_id' => 1, 'name' => 'YUVON 580 Vt'],
        ['category_id' => 1, 'name' => 'JA 2 tam 540 Vt'],
        ['category_id' => 1, 'name' => 'wingo 580 Vt'],

        ['category_id' => 2, 'name' => 'SALOX 5 kVt'],
        ['category_id' => 2, 'name' => 'SALOX 10 kVt'],
        ['category_id' => 2, 'name' => 'SALOX 15 kVt'],
        ['category_id' => 2, 'name' => 'SALOX 20 kVt'],
        ['category_id' => 2, 'name' => 'INTV 5 kVt'],
        ['category_id' => 2, 'name' => 'INTV 10 kVt'],
        ['category_id' => 2, 'name' => 'INTV 15 kVt'],
        ['category_id' => 2, 'name' => 'DEYE 5 kVt'],
        ['category_id' => 2, 'name' => 'DEYE 8 kVt'],
        ['category_id' => 2, 'name' => 'DEYE 10 + 12 kVt'],
        ['category_id' => 2, 'name' => 'DELTRON 4 kVt'],
        ['category_id' => 2, 'name' => 'DELTRON 6 kVt'],
        ['category_id' => 2, 'name' => 'DELTRON 11kVt dub'],
        ['category_id' => 2, 'name' => 'ANERIN 6,2 kVt'],
        ['category_id' => 2, 'name' => 'ANERIN 8,2 kVt'],
        ['category_id' => 2, 'name' => 'ANERIN 10,2 kVt'],
        ['category_id' => 2, 'name' => 'TTN 10.2 кВт'],
        ['category_id' => 2, 'name' => 'DELTA 6.2 кВт'],

        ['category_id' => 3, 'name' => 'Shit'],
        ['category_id' => 3, 'name' => 'Chastotnik'],

        ['category_id' => 4, 'name' => '100 Аh (sariq)'],
        ['category_id' => 4, 'name' => '100 Аh (yashil)'],
        ['category_id' => 4, 'name' => '100 Аh (qizil)'],

        ['category_id' => 5, 'name' => '48/200 AH'],
        ['category_id' => 5, 'name' => '48/100 AH'],

    ];

    public function run(): void
    {
        \DB::table((new Category())->getTable())->truncate();
        \DB::table((new Category())->getTable())->insert($this->categories);

        \DB::table((new Good())->getTable())->truncate();
        foreach ($this->products as $product) {
            Good::create($product);
        }
    }
}
