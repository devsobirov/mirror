<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        if (!DB::table('users')->where('phone', '934690797')->exists()) {
            User::create([
                'phone' => '934690797',
                'email' => 'devsobirov@mail.com',
                'name' => 'Админ',
                'role' => User::ADMIN,
                'password' => Hash::make('secret')
            ]);
        }
        if (!DB::table('users')->where('phone', '939443555')->exists()) {
            User::create([
                'phone' => '939443555',
                'email' => null,
                'name' => 'Direktor',
                'role' => User::ADMIN,
                'password' => Hash::make('secret')
            ]);
        }
        if (!DB::table('users')->where('phone', '990000000')->exists()) {
            User::create([
                'phone' => '990000000',
                'email' => null,
                'name' => 'Manager',
                'role' => User::MANAGER,
                'password' => Hash::make('secret')
            ]);
        }
    }
}
