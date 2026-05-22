<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Address::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // لكل يوزر (1 → 50)
        for ($i = 1; $i <= 200; $i++) {
            Address::create([
                'user_id' => $i,
                'store_id' => null,
                'type' => 'home',
                'address_ar' => 'عنوان تجريبي ' . $i,
                'address_en' => 'Test Address ' . $i,
                'address_details' => 'Details ' . $i,
                'phone' => '09999999' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'lat' => 33.5138,
                'lon' => 36.2765,
                'is_default' => true,
            ]);
        }
    }
}
