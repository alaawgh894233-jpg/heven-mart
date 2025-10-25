<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name_ar' => 'نايكي', 'name_en' => 'Nike'],
            ['name_ar' => 'أديداس', 'name_en' => 'Adidas'],
            ['name_ar' => 'زارا', 'name_en' => 'Zara'],
            ['name_ar' => 'إتش آند إم', 'name_en' => 'H&M'],
            ['name_ar' => 'بوما', 'name_en' => 'Puma'],
            ['name_ar' => 'ليفايز', 'name_en' => 'Levi’s'],
            ['name_ar' => 'غوتشي', 'name_en' => 'Gucci'],
            ['name_ar' => 'بيرشكا', 'name_en' => 'Bershka'],
            ['name_ar' => 'أندر آرمور', 'name_en' => 'Under Armour'],
            ['name_ar' => 'رالف لورين', 'name_en' => 'Ralph Lauren'],

            ['name_ar' => 'رولكس', 'name_en' => 'Rolex'],
            ['name_ar' => 'أوميغا', 'name_en' => 'Omega'],
            ['name_ar' => 'تيسو', 'name_en' => 'Tissot'],
            ['name_ar' => 'كاسيو', 'name_en' => 'Casio'],
            ['name_ar' => 'سيكو', 'name_en' => 'Seiko'],
            ['name_ar' => 'باتيك فيليب', 'name_en' => 'Patek Philippe'],
            ['name_ar' => 'تاغ هوير', 'name_en' => 'TAG Heuer'],
            ['name_ar' => 'فوسيل', 'name_en' => 'Fossil'],
            ['name_ar' => 'بولغاري', 'name_en' => 'Bulgari'],
            ['name_ar' => 'لونجين', 'name_en' => 'Longines'],

            ['name_ar' => 'سامسونج', 'name_en' => 'Samsung'],
            ['name_ar' => 'آبل', 'name_en' => 'Apple'],
            ['name_ar' => 'هواوي', 'name_en' => 'Huawei'],
            ['name_ar' => 'شاومي', 'name_en' => 'Xiaomi'],
            ['name_ar' => 'سوني', 'name_en' => 'Sony'],
            ['name_ar' => 'إل جي', 'name_en' => 'LG'],
            ['name_ar' => 'ديل', 'name_en' => 'Dell'],
            ['name_ar' => 'إتش بي', 'name_en' => 'HP'],
            ['name_ar' => 'لينوفو', 'name_en' => 'Lenovo'],
            ['name_ar' => 'ون بلس', 'name_en' => 'OnePlus'],

            ['name_ar' => 'سكيتشرز', 'name_en' => 'Skechers'],
            ['name_ar' => 'نيو بالانس', 'name_en' => 'New Balance'],
            ['name_ar' => 'ريبوك', 'name_en' => 'Reebok'],
            ['name_ar' => 'كونفيرس', 'name_en' => 'Converse'],
            ['name_ar' => 'فانز', 'name_en' => 'Vans'],
            ['name_ar' => 'ميريل', 'name_en' => 'Merrell'],
            ['name_ar' => 'تيمبرلاند', 'name_en' => 'Timberland'],
            ['name_ar' => 'لويس فويتون', 'name_en' => 'Louis Vuitton'],
            ['name_ar' => 'شانيل', 'name_en' => 'Chanel'],
            ['name_ar' => 'مايكل كورس', 'name_en' => 'Michael Kors'],

            ['name_ar' => 'كوتش', 'name_en' => 'Coach'],
            ['name_ar' => 'برادا', 'name_en' => 'Prada'],
            ['name_ar' => 'هيرمس', 'name_en' => 'Hermès'],
            ['name_ar' => 'ديور', 'name_en' => 'Dior'],
            ['name_ar' => 'توري بورش', 'name_en' => 'Tory Burch'],
            ['name_ar' => 'لونشان', 'name_en' => 'Longchamp'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name_ar' => $brand['name_ar'],
                'name_en' => $brand['name_en'],
                'image' => 'brands/default.png',
                'is_active' => true,
            ]);
        }
    }
}
