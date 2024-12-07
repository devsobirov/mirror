<?php

namespace Database\Seeders;

use App\Models\Warehouse\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    protected array $warehouses = [
        [
            'name' => 'Ombor 1',
            'active' => true,
        ],
        [
            'name' => 'Ombor 2',
            'active' => true,
        ],
        [
            'name' => 'Ombor 3',
            'active' => false,
        ]
    ];

    public function run(): void
    {
        $table = (new Warehouse())->getTable();

        foreach ($this->warehouses as $warehouse) {
            if (!\DB::table(($table))->where('name', $warehouse['name'])->exists()) {
                Warehouse::create($warehouse);
            }
        }
    }
}
