<?php

namespace Database\Seeders\Demo;

use App\Enums\Finance\DebtStatusEnum;
use App\Enums\Offer\OfferStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Demo orders (offers + offer_items), finance clients, debts, payments.
 * Run AFTER DemoCatalogSeeder + DemoSettingsSeeder.
 *
 * WIPES: offer_items, offers, payments, debts, clients.
 * Deterministic (fixed random seed) → same data on every run.
 *
 * Amount math is identical to resources/views/services/offer-form.blade.php:
 *   square_meter: dim = x*y*0.0001         perimeter: dim = (x+y)*2*0.01   none: dim = 1
 *   item_cost = full_price * dim * qty      (full_price = price + margin%)
 *   sub-item: qty = parent qty, x/y = parent x/y, dim by sub's own calculator
 *   parent cost = item_cost + Σ sub cost;  offer cost = Σ parent cost − discount
 */
class DemoOrdersSeeder extends Seeder
{
    const DAYS = 75;              // history length

    const FIRST_OFFER_ID = 1001;  // receipt numbers start here

    const NAMES = [
        'Aziz aka', 'Bekzod', 'Dilshod usta', 'Farrux', 'Gulnora opa', 'Jasur', 'Kamola', 'Laziz',
        'Mansur aka', 'Nodira', 'Otabek usta', 'Rustam aka', 'Sherzod', 'Shoxrux', 'Ulug\'bek',
        'Xurshid', 'Zarina', 'Sanjar', 'Malika', 'Eldor', 'Javohir mebel', 'Komil usta', 'Iroda',
        'Anvar aka', 'Bobur', 'Diyor', 'Sevara', 'Temur', 'Akmal mebel', 'Nilufar',
    ];

    const PREFIXES = ['90', '91', '93', '94', '97', '99', '88', '33', '95'];

    /** main-service name => [weight, x range cm, y range cm] */
    const MAINS = [
        'Ko\'zgu kumush 4 mm' => [30, [30, 120], [60, 200]],
        'Ko\'zgu kumush 4 mm (premium)' => [14, [40, 120], [80, 200]],
        'Oddiy oyna 4 mm' => [14, [30, 150], [30, 150]],
        'Ko\'zgu bronza' => [8, [40, 100], [60, 180]],
        'Oq xrustal oyna 4 mm' => [6, [30, 100], [30, 100]],
        'Ko\'zgu grafit (kulrang)' => [4, [40, 100], [60, 180]],
        'Mebel oynasi (eshik/vitrina uchun)' => [5, [30, 60], [60, 200]],
        'Oddiy oyna 6 mm' => [3, [60, 150], [60, 200]],
        'Kulrang (grafit) oyna' => [2, [30, 100], [30, 100]],
        'Ko\'zgu tillarang (zolotistka)' => [2, [40, 80], [60, 150]],
        'Ko\'zgu mat (tumanli)' => [2, [40, 80], [60, 150]],
        'Mat oyna (satinato)' => [2, [40, 80], [60, 200]],
        'Naqshli oyna (ko\'k gul)' => [2, [40, 80], [60, 150]],
        'Bronza tonirovka oyna' => [2, [40, 80], [60, 150]],
        'Rombik ko\'zgu kumush' => [2, [100, 250], [100, 280]],
        'Rombik ko\'zgu bronza' => [1, [100, 250], [100, 280]],
    ];

    /** sub-service name => probability to be attached to a glass/mirror item */
    const SUB_CHANCE = [
        'Fatset charxlash' => 45,
        'Qirrani charxlash (silliqlash)' => 30,
        'Oyna yuvish (tozalash)' => 20,
        'Orqa plyonka (oboy) yopishtirish' => 8,
        'Peskostruy ramka (chet bo\'yicha)' => 6,
        'Evrokromka charxlash' => 8,
        'Teshik ochish' => 6,
        'LED yoritish (ko\'zgu chetiga)' => 3,
        'O\'rnatish (oyna/ko\'zgu)' => 5,
        'O\'rnatish (rombik)' => 90,
        'Rombik uchun yelim' => 70,
    ];

    /** extra standalone lines: name => [chance %, qty range] */
    const EXTRAS = [
        'Yetkazib berish (shahar ichida)' => [12, [1, 1]],
        'Qavatga ko\'tarib chiqish' => [4, [2, 7]],
        'O\'lchov olish (usta chaqiruvi)' => [4, [1, 1]],
        'Ko\'zgu mahkamlagichi (derjatel)' => [6, [4, 8]],
        'PVX kromka 1×19 mm' => [3, [10, 40]],
        'PVX kromka 0.4×19 mm' => [3, [10, 60]],
        'Silikon yelim (ko\'zgu uchun)' => [3, [1, 2]],
        'Shoshilinch buyurtma (1 kunda)' => [2, [1, 1]],
    ];

    private array $svc = [];

    private array $subLinks = [];

    public function run(): void
    {
        mt_srand(20261124);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['offer_items', 'offers', 'payments', 'debts', 'clients'] as $t) {
            DB::table($t)->truncate();
        }
        DB::statement('ALTER TABLE offers AUTO_INCREMENT = '.self::FIRST_OFFER_ID);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->svc = DB::table('services')->get()->keyBy('name')->map(fn ($s) => (array) $s)->all();
        foreach (DB::table('service_subservices')->get() as $l) {
            $this->subLinks[$l->service_id][] = $l->subservice_id;
        }
        $byId = collect($this->svc)->keyBy('id')->all();

        $managers = DemoSettingsSeeder::demoUserIds() ?: [DB::table('users')->value('id')];
        $today = Carbon::today();
        $offerCount = 0;
        $offerIdsForDebt = [];

        for ($d = self::DAYS; $d >= 0; $d--) {
            $day = $today->copy()->subDays($d);
            if ($day->isSunday() && mt_rand(1, 100) > 30) {
                continue;
            }
            // business grows a bit over time
            $n = mt_rand(4, 8) + intdiv(self::DAYS - $d, 25);
            for ($i = 0; $i < $n; $i++) {
                $at = $day->copy()->setTime(mt_rand(9, 18), mt_rand(0, 59), mt_rand(0, 59));
                if ($at->isFuture()) {
                    $at = now()->subMinutes(mt_rand(5, 120));
                }
                $status = $d === 0
                    ? (mt_rand(1, 100) <= 35 ? OfferStatusEnum::Draft->value : OfferStatusEnum::Paid->value)
                    : ($d <= 2 && mt_rand(1, 100) <= 40 ? OfferStatusEnum::Paid->value : OfferStatusEnum::Completed->value);

                $items = $this->makeItems($byId);
                $sum = array_sum(array_column($items, 'cost'));
                $discount = ($sum > 1_000_000 && mt_rand(1, 100) <= 25) ? (int) (round($sum * 0.03 / 5000) * 5000) : 0;
                $phone = self::PREFIXES[array_rand(self::PREFIXES)].mt_rand(1000000, 9999999);

                $offerId = DB::table('offers')->insertGetId([
                    'cost' => round($sum - $discount, 2),
                    'status' => $status,
                    'discount' => $discount,
                    'client_id' => null,
                    'manager_id' => $managers[array_rand($managers)],
                    'client_name' => self::NAMES[array_rand(self::NAMES)],
                    'phone' => $phone,
                    'phone2' => null,
                    'comment' => mt_rand(1, 100) <= 10 ? 'Ertaga soat 10 da olib ketadi' : null,
                    'created_at' => $at,
                    'updated_at' => $at,
                ]);
                foreach ($items as $it) {
                    $subs = $it['_subs'];
                    unset($it['_subs']);
                    $parentId = DB::table('offer_items')->insertGetId($it + ['offer_id' => $offerId]);
                    foreach ($subs as $s) {
                        DB::table('offer_items')->insert($s + ['offer_id' => $offerId, 'parent_item_id' => $parentId]);
                    }
                }
                $offerCount++;
                if ($sum > 1_500_000 && $d > 3) {
                    $offerIdsForDebt[] = [$offerId, (int) ($sum - $discount), $at];
                }
            }
        }

        $this->seedFinance($offerIdsForDebt);
        $this->command?->info("Orders: $offerCount, items: ".DB::table('offer_items')->count()
            .', debts: '.DB::table('debts')->count().', payments: '.DB::table('payments')->count());
    }

    private function makeItems(array $byId): array
    {
        $items = [];
        $lines = mt_rand(1, 100) <= 60 ? 1 : mt_rand(2, 4);
        for ($l = 0; $l < $lines; $l++) {
            $name = $this->weighted(array_map(fn ($v) => $v[0], self::MAINS));
            [, $xr, $yr] = self::MAINS[$name];
            $s = $this->svc[$name];
            $x = mt_rand($xr[0], $xr[1]);
            $y = mt_rand($yr[0], $yr[1]);
            $qty = str_starts_with($name, 'Rombik') ? 1 : $this->weighted([1 => 50, 2 => 20, 3 => 8, 4 => 10, 6 => 6, 8 => 3, 12 => 3]);
            $item = $this->line($s, $qty, $x, $y);

            $subs = [];
            foreach ($this->subLinks[$s['id']] ?? [] as $subId) {
                $sub = $byId[$subId];
                if (mt_rand(1, 100) <= (self::SUB_CHANCE[$sub['name']] ?? 0)) {
                    $subQty = $sub['calculator_id'] ? $qty : ($sub['name'] === 'Teshik ochish' ? $qty * 2 : $qty);
                    $subs[] = $this->line($sub, $subQty, $x, $y);
                }
            }
            // charxlash and fatset are alternatives — keep only one
            $names = array_map(fn ($r) => $r['_name'], $subs);
            if (in_array('Fatset charxlash', $names) && in_array('Qirrani charxlash (silliqlash)', $names)) {
                $subs = array_values(array_filter($subs, fn ($r) => $r['_name'] !== 'Qirrani charxlash (silliqlash)'));
            }
            $item['cost'] = round($item['item_cost'] + array_sum(array_column($subs, 'cost')), 2);
            $item['_subs'] = array_map(fn ($r) => array_diff_key($r, ['_name' => 1]), $subs);
            $items[] = array_diff_key($item, ['_name' => 1]);
        }
        foreach (self::EXTRAS as $name => [$chance, $qr]) {
            if (mt_rand(1, 100) <= $chance) {
                $row = $this->line($this->svc[$name], mt_rand($qr[0], $qr[1]), null, null);
                $row['_subs'] = [];
                $items[] = array_diff_key($row, ['_name' => 1]);
            }
        }

        return $items;
    }

    /** One offer_items row computed exactly like the order form does */
    private function line(array $s, int $qty, ?int $x, ?int $y): array
    {
        $price = $s['margin'] ? $s['price'] + $s['price'] * $s['margin'] / 100 : $s['price'];
        $dim = 1.0;
        if ($x && $y && $s['calculator_id'] === 'square_meter') {
            $dim = round($x * $y * 0.0001, 4);
        } elseif ($x && $y && $s['calculator_id'] === 'perimeter') {
            $dim = round(($x + $y) * 2 * 0.01, 4);
        }
        $hasCalc = (bool) $s['calculator_id'];
        $cost = round($price * $qty * $dim, 2);

        return [
            '_name' => $s['name'],
            'service_id' => $s['id'],
            'price' => $price,
            'original_price' => $price,
            'quantity' => $qty,
            'x' => $hasCalc ? $x : null,
            'y' => $hasCalc ? $y : null,
            'dimension_amount' => $dim,
            'item_cost' => $cost,
            'cost' => $cost,
        ];
    }

    private function seedFinance(array $bigOffers): void
    {
        $clients = [
            ['Javohir Mebel MChJ', 'Chilonzor tumani, 9-mavze', '901112233'],
            ['Akmal mebel sexi', 'Sergeli, Yangi Sergeli ko\'chasi 12', '935554433'],
            ['Komil usta (ta\'mirlash)', 'Yunusobod, 4-kvartal', '977778899'],
            ['Interior Plus', 'Mirzo Ulug\'bek tumani', '998887766'],
            ['Dilshod qurilish', 'Olmazor tumani', '946665544'],
            ['Shoxrux (dizayner)', null, '883334455'],
        ];
        $clientIds = [];
        foreach ($clients as [$name, $addr, $phone]) {
            $clientIds[] = DB::table('clients')->insertGetId(['name' => $name, 'address' => $addr, 'phone' => $phone, 'phone2' => null]);
        }

        shuffle($bigOffers);
        $bigOffers = array_slice($bigOffers, 0, 12);
        foreach ($bigOffers as $k => [$offerId, $amount, $at]) {
            $clientId = $clientIds[$k % count($clientIds)];
            $at = Carbon::parse($at);
            $due = $at->copy()->addDays([7, 10, 14, 30][mt_rand(0, 3)]);
            $debtId = DB::table('debts')->insertGetId([
                'client_id' => $clientId,
                'amount' => $amount,
                'payment_date' => $due,
                'status' => DebtStatusEnum::Pending->value,
                'caption' => "Buyurtma #$offerId bo'yicha qarz",
                'offer_id' => $offerId,
                'created_at' => $at,
                'updated_at' => $at,
            ]);
            DB::table('offers')->where('id', $offerId)->update(['client_id' => $clientId]);

            // payments: some fully paid, some partially, some none yet
            $left = $amount;
            $plan = $k % 3; // 0 = full in 2 parts, 1 = partial, 2 = nothing yet
            $parts = $plan === 0 ? [0.5, 0.5] : ($plan === 1 ? [0.4] : []);
            $payAt = $at->copy();
            $ratings = [];
            foreach ($parts as $p) {
                $payAt = $payAt->copy()->addDays(mt_rand(2, 6));
                if ($payAt->isFuture()) {
                    break;
                }
                $sum = $p === 0.5 && $left < $amount ? $left : (int) (round($amount * $p / 1000) * 1000);
                $left -= $sum;
                $rating = mt_rand(3, 5);
                $ratings[] = $rating;
                DB::table('payments')->insert([
                    'client_id' => $clientId,
                    'debt_id' => $debtId,
                    'amount' => $sum,
                    'type' => mt_rand(1, 4),
                    'left_debt_amount' => $left,
                    'rating' => $rating,
                    'created_at' => $payAt,
                    'updated_at' => $payAt,
                ]);
            }
            $update = ['rating' => $ratings ? array_sum($ratings) / count($ratings) : null];
            if ($left <= 0) {
                $update += ['status' => DebtStatusEnum::Paid->value, 'closed_at' => $payAt];
            }
            DB::table('debts')->where('id', $debtId)->update($update);
        }
    }

    private function weighted(array $weights): string|int
    {
        $r = mt_rand(1, array_sum($weights));
        foreach ($weights as $k => $w) {
            if (($r -= $w) <= 0) {
                return $k;
            }
        }

        return array_key_first($weights);
    }
}
