<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//        User::truncate();

        $tokens = [];

        for ($i = 1; $i <= 200; $i++) {

            $user = User::create([
                'email' => "user$i@test.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'customer',
            ]);

            $token = $user->createToken('test-token')->plainTextToken;

            $tokens[] = [
                'user_id' => $user->id,
                'token' => $token
            ];
        }

        file_put_contents(
            storage_path('app/test_tokens.json'),
            json_encode($tokens)
        );
    }
}
