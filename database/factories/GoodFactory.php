<?php

namespace Database\Factories;

use App\Models\Warehouse\Good;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse\Good>
 */
class GoodFactory extends Factory
{

    protected $model = Good::class;

    public function definition(): array
    {
        $nameLength = rand(1, 3);
        $name = '';
        for ($i = 0; $i < $nameLength; $i++) {
            $w = $this->getWord();
            if (strlen($w) < 4) {
                $w .= $this->getWord();
            }
            $name .= ' ' . $w;
        }

        return [
            'name' => ucfirst($name),
            'category_id' => null,
            'price' => rand(500, 10000) * 1000,
            'status' => $this->faker->boolean(95),
            'created_at' => $this->faker->dateTimeInInterval('now', '-3 months'),
        ];
    }

    private function getWord(): string
    {
        return $this->faker->word;
    }
}
