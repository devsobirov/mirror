<?php

namespace Database\Seeders\Demo;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Demo receipt header ("Chop etish sozlamalari") + demo staff accounts.
 * Real users are NOT touched; demo users are upserted by phone.
 */
class DemoSettingsSeeder extends Seeder
{
    /** phone => [name, role, password] — login is by phone (9 digits, no +998) */
    const USERS = [
        '900000000' => ['Admin', User::ADMIN, 'admin123'],
        '900000001' => ['Menejer Dilnoza', User::MANAGER, 'demo123'],
        '900000002' => ['Usta Sardor', User::WORKER, 'demo123'],
    ];

    public function run(): void
    {
        DB::table('print_settings')->truncate();
        DB::table('print_settings')->insert([
            'organization' => 'Oyna Ustaxonasi',
            'phone1' => '+998 90 123-45-67',
            'phone2' => '+998 91 765-43-21',
            'type' => null,
            'thanks' => 'Xaridingiz uchun rahmat! Kafolat — 12 oy.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (self::USERS as $phone => [$name, $role, $password]) {
            User::updateOrCreate(['phone' => $phone], [
                'name' => $name,
                'role' => $role,
                'password' => Hash::make($password),
            ]);
        }
    }

    public static function demoUserIds(): array
    {
        return User::whereIn('phone', array_keys(self::USERS))->pluck('id')->all();
    }
}
