<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [

            ['name_ar' => 'كتب', 'name_en' => 'Books', 'image' => 'categories/cat1.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'قرطاسية', 'name_en' => 'Stationery', 'image' => 'categories/cat2.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ألعاب تعليمية', 'name_en' => 'Learning Toys', 'image' => 'categories/cat3.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أجهزة مدرسية', 'name_en' => 'School Devices', 'image' => 'categories/cat4.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'حقائب', 'name_en' => 'Bags', 'image' => 'categories/cat5.jpg', 'status' => 'active', 'parent_id' => null],

            ['name_ar' => 'كمبيوتر', 'name_en' => 'Computers', 'image' => 'categories/cat6.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'هواتف', 'name_en' => 'Phones', 'image' => 'categories/cat7.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'إكسسوارات', 'name_en' => 'Accessories', 'image' => 'categories/cat8.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ألعاب إلكترونية', 'name_en' => 'Electronic Games', 'image' => 'categories/cat9.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'برمجيات', 'name_en' => 'Software', 'image' => 'categories/cat10.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'ملابس نسائية', 'name_en' => 'Women Clothes', 'image' => 'categories/cat11.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ملابس رجالية', 'name_en' => 'Men Clothes', 'image' => 'categories/cat12.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أحذية', 'name_en' => 'Shoes', 'image' => 'categories/cat13.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'إكسسوارات', 'name_en' => 'Accessories', 'image' => 'categories/cat14.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'ملابس أطفال', 'name_en' => 'Kids Clothes', 'image' => 'categories/cat15.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ألعاب أطفال', 'name_en' => 'Kids Toys', 'image' => 'categories/cat16.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'عربات', 'name_en' => 'Strollers', 'image' => 'categories/cat17.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مستلزمات المواليد', 'name_en' => 'Baby Essentials', 'image' => 'categories/cat18.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'جوالات', 'name_en' => 'Phones', 'image' => 'categories/cat19.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ساعات ذكية', 'name_en' => 'Smart Watches', 'image' => 'categories/cat20.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'سماعات', 'name_en' => 'Headphones', 'image' => 'categories/cat21.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أجهزة تتبع', 'name_en' => 'Trackers', 'image' => 'categories/cat22.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'عروض يومية', 'name_en' => 'Daily Deals', 'image' => 'categories/cat23.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'منتجات منزلية', 'name_en' => 'Home Products', 'image' => 'categories/cat24.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics', 'image' => 'categories/cat25.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ملابس', 'name_en' => 'Clothes', 'image' => 'categories/cat26.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'أغذية', 'name_en' => 'Groceries', 'image' => 'categories/cat27.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مشروبات', 'name_en' => 'Drinks', 'image' => 'categories/cat28.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'منتجات عضوية', 'name_en' => 'Organic Products', 'image' => 'categories/cat29.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'معلبات', 'name_en' => 'Canned Food', 'image' => 'categories/cat30.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'أجهزة منزلية', 'name_en' => 'Home Appliances', 'image' => 'categories/cat31.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'تلفزيونات', 'name_en' => 'TVs', 'image' => 'categories/cat32.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مكيفات', 'name_en' => 'ACs', 'image' => 'categories/cat33.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'كاميرات', 'name_en' => 'Cameras', 'image' => 'categories/cat34.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'ملابس', 'name_en' => 'Clothes', 'image' => 'categories/cat35.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'حقائب', 'name_en' => 'Bags', 'image' => 'categories/cat36.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ساعات', 'name_en' => 'Watches', 'image' => 'categories/cat37.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'نظارات', 'name_en' => 'Glasses', 'image' => 'categories/cat38.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'كتب علمية', 'name_en' => 'Science Books', 'image' => 'categories/cat39.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'روايات', 'name_en' => 'Novels', 'image' => 'categories/cat40.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'قصص أطفال', 'name_en' => 'Kids Stories', 'image' => 'categories/cat41.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أدوات مكتبية', 'name_en' => 'Office Supplies', 'image' => 'categories/cat42.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'أثاث', 'name_en' => 'Furniture', 'image' => 'categories/cat43.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مطبخ', 'name_en' => 'Kitchen', 'image' => 'categories/cat44.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'حمام', 'name_en' => 'Bathroom', 'image' => 'categories/cat45.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ديكور', 'name_en' => 'Decoration', 'image' => 'categories/cat46.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'مستحضرات تجميل', 'name_en' => 'Cosmetics', 'image' => 'categories/cat47.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'عطور', 'name_en' => 'Perfumes', 'image' => 'categories/cat48.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'منتجات شعر', 'name_en' => 'Hair Products', 'image' => 'categories/cat49.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'عناية بالبشرة', 'name_en' => 'Skin Care', 'image' => 'categories/cat50.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'معدات رياضية', 'name_en' => 'Sports Equipment', 'image' => 'categories/cat51.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ملابس رياضية', 'name_en' => 'Sports Clothes', 'image' => 'categories/cat52.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أحذية رياضية', 'name_en' => 'Sports Shoes', 'image' => 'categories/cat53.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مكملات غذائية', 'name_en' => 'Supplements', 'image' => 'categories/cat54.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'ألعاب', 'name_en' => 'Toys', 'image' => 'categories/cat55.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'هدايا', 'name_en' => 'Gifts', 'image' => 'categories/cat56.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'بطاقات معايدة', 'name_en' => 'Greeting Cards', 'image' => 'categories/cat57.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ديكورات', 'name_en' => 'Decorations', 'image' => 'categories/cat58.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'حقائب سفر', 'name_en' => 'Travel Bags', 'image' => 'categories/cat59.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'معدات تخييم', 'name_en' => 'Camping Gear', 'image' => 'categories/cat60.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'خرائط وأدلة', 'name_en' => 'Maps & Guides', 'image' => 'categories/cat61.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أجهزة ملاحة', 'name_en' => 'Navigation Devices', 'image' => 'categories/cat62.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'أقلام', 'name_en' => 'Pens', 'image' => 'categories/cat63.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'دفاتر', 'name_en' => 'Notebooks', 'image' => 'categories/cat64.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ملفات', 'name_en' => 'Folders', 'image' => 'categories/cat65.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مستلزمات أخرى', 'name_en' => 'Other Supplies', 'image' => 'categories/cat66.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'خضروات وفواكه', 'name_en' => 'Fruits & Vegetables', 'image' => 'categories/cat67.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'حلويات', 'name_en' => 'Sweets', 'image' => 'categories/cat68.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'منتجات الألبان', 'name_en' => 'Dairy Products', 'image' => 'categories/cat69.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'لحوم', 'name_en' => 'Meats', 'image' => 'categories/cat70.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'حاسبات', 'name_en' => 'Computers', 'image' => 'categories/cat71.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'هواتف ذكية', 'name_en' => 'Smartphones', 'image' => 'categories/cat72.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'أجهزة تابلت', 'name_en' => 'Tablets', 'image' => 'categories/cat73.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'سماعات رأس', 'name_en' => 'Headphones', 'image' => 'categories/cat74.jpg', 'status' => 'active', 'parent_id' => null],


            ['name_ar' => 'أدوية', 'name_en' => 'Medicines', 'image' => 'categories/cat75.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'مكملات', 'name_en' => 'Supplements', 'image' => 'categories/cat76.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'معدات طبية', 'name_en' => 'Medical Equipment', 'image' => 'categories/cat77.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'منتجات طبيعية', 'name_en' => 'Natural Products', 'image' => 'categories/cat78.jpg', 'status' => 'active', 'parent_id' => null],

            ['name_ar' => 'منتجات منزلية', 'name_en' => 'Home Products', 'image' => 'categories/cat79.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics', 'image' => 'categories/cat80.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ملابس', 'name_en' => 'Clothes', 'image' => 'categories/cat81.jpg', 'status' => 'active', 'parent_id' => null],
            ['name_ar' => 'ألعاب', 'name_en' => 'Toys', 'image' => 'categories/cat82.jpg', 'status' => 'active', 'parent_id' => null],
        ];

    //   DB::table('categories')->insert($categories);
        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('تم إضافة كل التصنيفات لجميع المنتجات بنجاح!');
    }
}
