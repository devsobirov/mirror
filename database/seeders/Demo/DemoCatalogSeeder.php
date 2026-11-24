<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Demo catalog: service categories, services, sub-service links + SVG tiles.
 *
 * WIPES: service_subservices, services, service_categories.
 * Edit the CATALOG array below and re-run:
 *   ./dev art mirror-workshop/public_html db:seed --class="Database\Seeders\Demo\DemoCatalogSeeder"
 *
 * Service keys:
 *   name   – display name (UZ latin), must be unique — sub-service links refer to it
 *   unit   – unit label shown in order/receipt (m², m, dona, reys, km, qavat)
 *   price  – base price UZS per 1 unit; selling price = price + margin%
 *   margin – markup % (optional, default 0)
 *   calc   – SQ (m² = x*y/10000), PER (perimeter m = 2*(x+y)/100) or null (plain quantity)
 *   sub    – true: can be attached to a main service as "qo'shimcha xizmat"
 *   subs   – list of sub-service names offered with this main service
 *   tint   – [light, dark] colors for the generated SVG tile; omit for no image
 *   active – default true
 */
class DemoCatalogSeeder extends Seeder
{
    const SQ = 'square_meter';

    const PER = 'perimeter';

    /** Sub-services typically offered with any glass / mirror sheet */
    const GLASS_SUBS = [
        'Fatset charxlash', 'Qirrani charxlash (silliqlash)', 'Evrokromka charxlash',
        'Peskostruy ramka (chet bo\'yicha)', 'Orqa plyonka (oboy) yopishtirish',
        'Teshik ochish', 'Kalit (tutqich) o\'yig\'i', 'LED yoritish (ko\'zgu chetiga)',
        'Oyna yuvish (tozalash)', 'O\'rnatish (oyna/ko\'zgu)',
    ];

    public static function catalog(): array
    {
        $silver = ['#f4f6f8', '#9aa3ad'];
        $glass = ['#eefaf8', '#9fd3cc'];

        return [
            'Oynalar' => [
                ['name' => 'Oddiy oyna 4 mm', 'unit' => 'm²', 'price' => 65000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => $glass],
                ['name' => 'Oddiy oyna 6 mm', 'unit' => 'm²', 'price' => 110000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#e6f6f3', '#7fbfb6']],
                ['name' => 'Oq xrustal oyna 4 mm', 'unit' => 'm²', 'price' => 90000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#ffffff', '#cfe3ea']],
                ['name' => 'Kulrang (grafit) oyna', 'unit' => 'm²', 'price' => 90000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#b9bec4', '#5b6168']],
                ['name' => 'Bronza tonirovka oyna', 'unit' => 'm²', 'price' => 90000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#e3c9a8', '#8a6a48']],
                ['name' => 'Naqshli oyna (ko\'k gul)', 'unit' => 'm²', 'price' => 85000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#dbe9fb', '#5d8fd0']],
                ['name' => 'Mat oyna (satinato)', 'unit' => 'm²', 'price' => 95000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#f1f3f4', '#c4cacd']],
                ['name' => 'Mebel oynasi (eshik/vitrina uchun)', 'unit' => 'm²', 'price' => 100000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#e9f5f1', '#88b9a8']],
            ],
            'Ko\'zgular' => [
                ['name' => 'Ko\'zgu kumush 4 mm', 'unit' => 'm²', 'price' => 70000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => $silver],
                ['name' => 'Ko\'zgu kumush 4 mm (premium)', 'unit' => 'm²', 'price' => 75000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#fbfcfd', '#8c96a2']],
                ['name' => 'Ko\'zgu kumush 6 mm', 'unit' => 'm²', 'price' => 140000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#eef1f4', '#7c8794']],
                ['name' => 'Ko\'zgu bronza', 'unit' => 'm²', 'price' => 115000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#ecd3b4', '#9c7549']],
                ['name' => 'Ko\'zgu grafit (kulrang)', 'unit' => 'm²', 'price' => 130000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#c3c7cc', '#4f555c']],
                ['name' => 'Ko\'zgu tillarang (zolotistka)', 'unit' => 'm²', 'price' => 180000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#f6e3a8', '#b88a2c']],
                ['name' => 'Ko\'zgu mat (tumanli)', 'unit' => 'm²', 'price' => 85000, 'margin' => 10, 'calc' => self::SQ, 'subs' => self::GLASS_SUBS, 'tint' => ['#eceff1', '#aab3ba']],
            ],
            'Rombik ko\'zgular' => [
                ['name' => 'Rombik ko\'zgu kumush', 'unit' => 'm²', 'price' => 350000, 'calc' => self::SQ, 'subs' => ['O\'rnatish (rombik)', 'Rombik uchun yelim'], 'tint' => ['#f2f4f6', '#8e98a3']],
                ['name' => 'Rombik ko\'zgu bronza', 'unit' => 'm²', 'price' => 400000, 'calc' => self::SQ, 'subs' => ['O\'rnatish (rombik)', 'Rombik uchun yelim'], 'tint' => ['#e8cba6', '#94693c']],
                ['name' => 'Rombik ko\'zgu tillarang', 'unit' => 'm²', 'price' => 500000, 'calc' => self::SQ, 'subs' => ['O\'rnatish (rombik)', 'Rombik uchun yelim'], 'tint' => ['#f5e1a0', '#b08326']],
                ['name' => 'O\'rnatish (rombik)', 'unit' => 'm²', 'price' => 80000, 'calc' => self::SQ, 'sub' => true],
                ['name' => 'Rombik uchun yelim', 'unit' => 'dona', 'price' => 10000, 'sub' => true],
                ['name' => 'Baget ramka', 'unit' => 'm', 'price' => 100000, 'tint' => ['#e9d7b9', '#8b6a3e']],
            ],
            'Qirra ishlov berish' => [
                ['name' => 'Fatset charxlash', 'unit' => 'm', 'price' => 4000, 'calc' => self::PER, 'sub' => true],
                ['name' => 'Qirrani charxlash (silliqlash)', 'unit' => 'm', 'price' => 3500, 'calc' => self::PER, 'sub' => true],
                ['name' => 'Evrokromka charxlash', 'unit' => 'm', 'price' => 5000, 'calc' => self::PER, 'sub' => true],
                ['name' => 'Teshik ochish', 'unit' => 'dona', 'price' => 15000, 'sub' => true],
                ['name' => 'Kalit (tutqich) o\'yig\'i', 'unit' => 'dona', 'price' => 20000, 'sub' => true],
            ],
            'Bezak va dekor' => [
                ['name' => 'Peskostruy ramka (chet bo\'yicha)', 'unit' => 'm', 'price' => 15000, 'calc' => self::PER, 'sub' => true],
                ['name' => 'Peskostruy naqsh (to\'liq yuza)', 'unit' => 'm²', 'price' => 60000, 'calc' => self::SQ, 'sub' => true],
                ['name' => 'Gravirovka', 'unit' => 'dona', 'price' => 50000, 'sub' => true],
                ['name' => 'Mebel oynasiga naqsh (uzor)', 'unit' => 'm²', 'price' => 80000, 'calc' => self::SQ, 'sub' => true],
                ['name' => 'Orqa plyonka (oboy) yopishtirish', 'unit' => 'm²', 'price' => 40000, 'calc' => self::SQ, 'sub' => true],
                ['name' => 'LED yoritish (ko\'zgu chetiga)', 'unit' => 'm', 'price' => 35000, 'calc' => self::PER, 'sub' => true],
            ],
            'PVX kromka' => [
                ['name' => 'PVX kromka 0.4×19 mm', 'unit' => 'm', 'price' => 2500],
                ['name' => 'PVX kromka 1×19 mm', 'unit' => 'm', 'price' => 3500],
                ['name' => 'PVX kromka 1×21 mm', 'unit' => 'm', 'price' => 4000],
                ['name' => 'PVX kromka 2×21 mm', 'unit' => 'm', 'price' => 5500],
                ['name' => 'PVX kromka 1×35 mm', 'unit' => 'm', 'price' => 6500],
                ['name' => 'PVX kromka 2×35 mm', 'unit' => 'm', 'price' => 9000],
                ['name' => 'PVX kromka Gold 1×35 mm', 'unit' => 'm', 'price' => 9000],
                ['name' => 'Kromkalash (mijoz materiali)', 'unit' => 'm', 'price' => 2000],
            ],
            'Yetkazish va o\'rnatish' => [
                ['name' => 'O\'lchov olish (usta chaqiruvi)', 'unit' => 'dona', 'price' => 30000],
                ['name' => 'O\'rnatish (oyna/ko\'zgu)', 'unit' => 'm²', 'price' => 40000, 'calc' => self::SQ, 'sub' => true],
                ['name' => 'Oyna yuvish (tozalash)', 'unit' => 'm', 'price' => 1000, 'calc' => self::PER, 'sub' => true],
                ['name' => 'Eski oynani demontaj qilish', 'unit' => 'm²', 'price' => 20000, 'calc' => self::SQ],
                ['name' => 'Yetkazib berish (shahar ichida)', 'unit' => 'reys', 'price' => 50000],
                ['name' => 'Yetkazib berish (shahar tashqarisi)', 'unit' => 'km', 'price' => 3000],
                ['name' => 'Qavatga ko\'tarib chiqish', 'unit' => 'qavat', 'price' => 10000],
                ['name' => 'Shoshilinch buyurtma (1 kunda)', 'unit' => 'dona', 'price' => 50000],
                ['name' => 'Frezerda o\'yish (Rover)', 'unit' => 'dona', 'price' => 90000],
            ],
            'Furnitura' => [
                ['name' => 'Ko\'zgu mahkamlagichi (derjatel)', 'unit' => 'dona', 'price' => 5000],
                ['name' => 'Silikon yelim (ko\'zgu uchun)', 'unit' => 'dona', 'price' => 40000],
                ['name' => 'Ikki tomonlama skotch', 'unit' => 'm', 'price' => 3000],
                ['name' => 'Oyna tutqichi (ruchka)', 'unit' => 'dona', 'price' => 25000],
            ],
        ];
    }

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('service_subservices')->truncate();
        DB::table('services')->truncate();
        DB::table('service_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $imgDir = public_path('assets/images/services/demo');
        File::ensureDirectoryExists($imgDir);

        $ids = [];
        $links = [];
        $now = now();

        foreach (self::catalog() as $title => $services) {
            $catId = DB::table('service_categories')->insertGetId(['title' => $title]);
            foreach ($services as $s) {
                $image = null;
                if (! empty($s['tint'])) {
                    $file = Str::slug($s['name']).'.svg';
                    File::put("$imgDir/$file", $this->tile($s['name'], $s['tint']));
                    $image = "assets/images/services/demo/$file";
                }
                $ids[$s['name']] = DB::table('services')->insertGetId([
                    'name' => $s['name'],
                    'image' => $image,
                    'category_id' => $catId,
                    'active' => $s['active'] ?? true,
                    'is_sub_service' => $s['sub'] ?? false,
                    'unit' => $s['unit'],
                    'price' => $s['price'],
                    'margin' => $s['margin'] ?? 0,
                    'calculator_id' => $s['calc'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                foreach ($s['subs'] ?? [] as $sub) {
                    $links[] = [$s['name'], $sub];
                }
            }
        }

        foreach ($links as [$main, $sub]) {
            if (! isset($ids[$sub])) {
                throw new \RuntimeException("Unknown sub-service '$sub' for '$main'");
            }
            DB::table('service_subservices')->insert(['service_id' => $ids[$main], 'subservice_id' => $ids[$sub]]);
        }

        $this->command?->info(sprintf('Catalog: %d categories, %d services, %d sub-links',
            count(self::catalog()), count($ids), count($links)));
    }

    /** Simple "glass sheet" SVG tile: gradient + glare stripes + name label */
    private function tile(string $name, array $tint): string
    {
        [$light, $dark] = $tint;
        $label = htmlspecialchars(Str::limit($name, 24), ENT_XML1);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="400" height="400">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="$light"/><stop offset="1" stop-color="$dark"/>
    </linearGradient>
  </defs>
  <rect width="400" height="400" fill="#f3f4f6"/>
  <rect x="40" y="30" width="320" height="280" rx="6" fill="url(#g)" stroke="#6b7280" stroke-opacity=".35" stroke-width="4"/>
  <polygon points="120,30 175,30 75,310 40,310 40,250" fill="#fff" fill-opacity=".35"/>
  <polygon points="200,30 220,30 120,310 100,310" fill="#fff" fill-opacity=".25"/>
  <text x="200" y="360" text-anchor="middle" font-family="Arial, sans-serif" font-size="22" font-weight="600" fill="#374151">$label</text>
</svg>
SVG;
    }
}
