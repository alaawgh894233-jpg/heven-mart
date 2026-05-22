<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name_ar' => 'كتب', 'name_en' => 'Books', 'image' => 'categories/cat1.jpg'],
            ['name_ar' => 'قرطاسية', 'name_en' => 'Stationery', 'image' => 'categories/cat2.jpg'],
            ['name_ar' => 'ألعاب تعليمية', 'name_en' => 'Learning Toys', 'image' => 'categories/cat3.jpg'],
            ['name_ar' => 'أجهزة مدرسية', 'name_en' => 'School Devices', 'image' => 'categories/cat4.jpg'],
            ['name_ar' => 'حقائب', 'name_en' => 'Bags', 'image' => 'categories/cat5.jpg'],

            ['name_ar' => 'كمبيوتر', 'name_en' => 'Computers', 'image' => 'categories/cat6.jpg'],
            ['name_ar' => 'هواتف', 'name_en' => 'Phones', 'image' => 'categories/cat7.jpg'],
            ['name_ar' => 'إكسسوارات', 'name_en' => 'Accessories', 'image' => 'categories/cat8.jpg'],
            ['name_ar' => 'ألعاب إلكترونية', 'name_en' => 'Electronic Games', 'image' => 'categories/cat9.jpg'],
            ['name_ar' => 'برمجيات', 'name_en' => 'Software', 'image' => 'categories/cat10.jpg'],

            ['name_ar' => 'ملابس نسائية', 'name_en' => 'Women Clothes', 'image' => 'categories/cat11.jpg'],
            ['name_ar' => 'ملابس رجالية', 'name_en' => 'Men Clothes', 'image' => 'categories/cat12.jpg'],
            ['name_ar' => 'أحذية', 'name_en' => 'Shoes', 'image' => 'categories/cat13.jpg'],
            ['name_ar' => 'إكسسوارات', 'name_en' => 'Accessories', 'image' => 'categories/cat14.jpg'],

            ['name_ar' => 'ملابس أطفال', 'name_en' => 'Kids Clothes', 'image' => 'categories/cat15.jpg'],
            ['name_ar' => 'ألعاب أطفال', 'name_en' => 'Kids Toys', 'image' => 'categories/cat16.jpg'],
            ['name_ar' => 'عربات', 'name_en' => 'Strollers', 'image' => 'categories/cat17.jpg'],
            ['name_ar' => 'مستلزمات المواليد', 'name_en' => 'Baby Essentials', 'image' => 'categories/cat18.jpg'],

            ['name_ar' => 'جوالات', 'name_en' => 'Phones', 'image' => 'categories/cat19.jpg'],
            ['name_ar' => 'ساعات ذكية', 'name_en' => 'Smart Watches', 'image' => 'categories/cat20.jpg'],
            ['name_ar' => 'سماعات', 'name_en' => 'Headphones', 'image' => 'categories/cat21.jpg'],
            ['name_ar' => 'أجهزة تتبع', 'name_en' => 'Trackers', 'image' => 'categories/cat22.jpg'],

            ['name_ar' => 'معدات رياضية', 'name_en' => 'Sports Equipment', 'image' => 'categories/cat51.jpg'],
            ['name_ar' => 'ملابس رياضية', 'name_en' => 'Sports Clothes', 'image' => 'categories/cat52.jpg'],
            ['name_ar' => 'أحذية رياضية', 'name_en' => 'Sports Shoes', 'image' => 'categories/cat53.jpg'],
            ['name_ar' => 'مكملات غذائية', 'name_en' => 'Supplements', 'image' => 'categories/cat54.jpg'],

            ['name_ar' => 'ألعاب', 'name_en' => 'Toys', 'image' => 'categories/cat55.jpg'],
            ['name_ar' => 'هدايا', 'name_en' => 'Gifts', 'image' => 'categories/cat56.jpg'],
            ['name_ar' => 'بطاقات معايدة', 'name_en' => 'Greeting Cards', 'image' => 'categories/cat57.jpg'],
            ['name_ar' => 'ديكورات', 'name_en' => 'Decorations', 'image' => 'categories/cat58.jpg'],

            ['name_ar' => 'حقائب سفر', 'name_en' => 'Travel Bags', 'image' => 'categories/cat59.jpg'],
            ['name_ar' => 'معدات تخييم', 'name_en' => 'Camping Gear', 'image' => 'categories/cat60.jpg'],
            ['name_ar' => 'خرائط وأدلة', 'name_en' => 'Maps & Guides', 'image' => 'categories/cat61.jpg'],
            ['name_ar' => 'أجهزة ملاحة', 'name_en' => 'Navigation Devices', 'image' => 'categories/cat62.jpg'],

            ['name_ar' => 'أقلام', 'name_en' => 'Pens', 'image' => 'categories/cat63.jpg'],
            ['name_ar' => 'دفاتر', 'name_en' => 'Notebooks', 'image' => 'categories/cat64.jpg'],
            ['name_ar' => 'ملفات', 'name_en' => 'Folders', 'image' => 'categories/cat65.jpg'],
            ['name_ar' => 'مستلزمات أخرى', 'name_en' => 'Other Supplies', 'image' => 'categories/cat66.jpg'],

            ['name_ar' => 'خضروات وفواكه', 'name_en' => 'Fruits & Vegetables', 'image' => 'categories/cat67.jpg'],
            ['name_ar' => 'حلويات', 'name_en' => 'Sweets', 'image' => 'categories/cat68.jpg'],
            ['name_ar' => 'منتجات الألبان', 'name_en' => 'Dairy Products', 'image' => 'categories/cat69.jpg'],
            ['name_ar' => 'لحوم', 'name_en' => 'Meats', 'image' => 'categories/cat70.jpg'],

            ['name_ar' => 'حاسبات', 'name_en' => 'Computers', 'image' => 'categories/cat71.jpg'],
            ['name_ar' => 'هواتف ذكية', 'name_en' => 'Smartphones', 'image' => 'categories/cat72.jpg'],
            ['name_ar' => 'أجهزة تابلت', 'name_en' => 'Tablets', 'image' => 'categories/cat73.jpg'],
            ['name_ar' => 'سماعات رأس', 'name_en' => 'Headphones', 'image' => 'categories/cat74.jpg'],

            ['name_ar' => 'أدوية', 'name_en' => 'Medicines', 'image' => 'categories/cat75.jpg'],
            ['name_ar' => 'مكملات', 'name_en' => 'Supplements', 'image' => 'categories/cat76.jpg'],
            ['name_ar' => 'معدات طبية', 'name_en' => 'Medical Equipment', 'image' => 'categories/cat77.jpg'],
            ['name_ar' => 'منتجات طبيعية', 'name_en' => 'Natural Products', 'image' => 'categories/cat78.jpg'],

            ['name_ar' => 'منتجات منزلية', 'name_en' => 'Home Products', 'image' => 'categories/cat79.jpg'],
            ['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics', 'image' => 'categories/cat80.jpg'],
            ['name_ar' => 'ملابس', 'name_en' => 'Clothes', 'image' => 'categories/cat81.jpg'],
            ['name_ar' => 'ألعاب', 'name_en' => 'Toys', 'image' => 'categories/cat82.jpg'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name_ar' => $category['name_ar'],
                'name_en' => $category['name_en'],
                'image' => $category['image'],
                'description_ar' => null,
                'description_en' => null,
                'is_active' => 1,
                'parent_id' => null,
            ]);
        }

        $this->command->info('Categories seeded successfully 🔥');
    }
}
