<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

/**
 * Full demo reset for screenshots (local only!).
 *   ./dev art mirror-workshop/public_html db:seed --class="Database\Seeders\Demo\DemoSeeder"
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException('DemoSeeder wipes orders/catalog — refusing to run in production.');
        }
        $this->call([
            DemoSettingsSeeder::class,
            DemoCatalogSeeder::class,
            DemoOrdersSeeder::class,
        ]);
        cache()->flush();
    }
}
