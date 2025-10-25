<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate(); // ← هذا يحذف كل المستخدمين
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 5 Admins
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'email' => "admin$i@example.com",
                'password' => Hash::make('password'), // كلمة المرور: password
                'email_verified_at' => now(),
                'role' => 'admin',
            ]);
        }

        // 20 Sellers
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'email' => "seller$i@example.com",
                'password' => Hash::make('password'), // كلمة المرور: password
                'email_verified_at' => now(),
                'role' => 'seller',
            ]);
        }
//        50 users
            for ($i = 1; $i <= 50; $i++) {
                User::create([
                    'email' => "user$i@example.com",
                    'password' => Hash::make('password'), // كلمة المرور: password
                    'email_verified_at' => now(),
                    'role' => 'customer',
                ]);
        }
    }
}
