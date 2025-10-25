<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
//            AttributeSeeder::class,
            BrandSeeder::class,
//            CategorySeeder::class,
            UserSeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            ]);

//        Category::factory(20)->create();
//        Category::factory(20)->create();
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
