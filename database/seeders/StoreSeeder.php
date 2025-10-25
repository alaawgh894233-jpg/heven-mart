<?php

namespace Database\Seeders;

use App\Models\Store;
//use Illuminate\Database\Seeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Store::truncate(); // ← هذا يحذف كل المتاجر القديمة
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $stores = [
            ['ar' => 'عالم التعلم', 'en' => 'Learning World', 'desc_ar' => 'كل ما تحتاجه للتعليم والتطوير الذاتي.', 'desc_en' => 'Everything you need for learning and self-development.'],
            ['ar' => 'سوق التقنية', 'en' => 'Tech Market', 'desc_ar' => 'أحدث الأجهزة والإلكترونيات في مكان واحد.', 'desc_en' => 'The latest gadgets and electronics in one place.'],
            ['ar' => 'ركن الموضة', 'en' => 'Fashion Corner', 'desc_ar' => 'أزياء عصرية تناسب جميع الأذواق.', 'desc_en' => 'Trendy fashion for all tastes.'],
            ['ar' => 'مخبوزات الخير', 'en' => 'Al-Khair Bakery', 'desc_ar' => 'ألذ المخبوزات الطازجة يومياً.', 'desc_en' => 'Delicious fresh baked goods daily.'],
            ['ar' => 'إلكترونيات بلا حدود', 'en' => 'Electronics Unlimited', 'desc_ar' => 'تسوق أحدث المنتجات الإلكترونية.', 'desc_en' => 'Shop the latest electronic products.'],
            ['ar' => 'الكتاب الذهبي', 'en' => 'Golden Bookstore', 'desc_ar' => 'أفضل الكتب في جميع المجالات.', 'desc_en' => 'Top books in all fields.'],
            ['ar' => 'عالم الألعاب', 'en' => 'Games World', 'desc_ar' => 'ألعاب لجميع الأعمار بأسعار منافسة.', 'desc_en' => 'Games for all ages at great prices.'],
            ['ar' => 'قهوة الصباح', 'en' => 'Morning Coffee', 'desc_ar' => 'قهوتك المفضلة تبدأ من هنا.', 'desc_en' => 'Your favorite coffee starts here.'],
            ['ar' => 'أساسيات المنزل', 'en' => 'Home Essentials', 'desc_ar' => 'كل ما يحتاجه منزلك من أدوات.', 'desc_en' => 'All your home essentials in one place.'],
            ['ar' => 'مستلزمات الطفل', 'en' => 'Baby Needs', 'desc_ar' => 'منتجات آمنة وعملية للأطفال.', 'desc_en' => 'Safe and practical baby products.'],
            ['ar' => 'سوق الطبيعة', 'en' => 'Nature Market', 'desc_ar' => 'منتجات طبيعية وصحية 100٪.', 'desc_en' => '100% natural and healthy products.'],
            ['ar' => 'الرياضة للجميع', 'en' => 'Sport for All', 'desc_ar' => 'معدات رياضية لكل المستويات.', 'desc_en' => 'Sports gear for every level.'],
            ['ar' => 'الموضة اليومية', 'en' => 'Daily Fashion', 'desc_ar' => 'أناقة يومية بأسعار مريحة.', 'desc_en' => 'Daily style at affordable prices.'],
            ['ar' => 'أجهزة المستقبل', 'en' => 'Future Devices', 'desc_ar' => 'اكتشف التكنولوجيا القادمة.', 'desc_en' => 'Discover future tech today.'],
            ['ar' => 'عطور الشرق', 'en' => 'Oriental Perfumes', 'desc_ar' => 'عطور عربية فاخرة وأصيلة.', 'desc_en' => 'Authentic oriental luxury perfumes.'],
            ['ar' => 'أدوات المدرسة', 'en' => 'School Supplies', 'desc_ar' => 'كل ما يحتاجه الطالب في عامه الدراسي.', 'desc_en' => 'Everything students need for school.'],
            ['ar' => 'مملكة الهواتف', 'en' => 'Phone Kingdom', 'desc_ar' => 'أحدث الهواتف والإكسسوارات.', 'desc_en' => 'Latest phones and accessories.'],
            ['ar' => 'جمال وأناقة', 'en' => 'Beauty & Style', 'desc_ar' => 'منتجات تجميل وعناية بالبشرة.', 'desc_en' => 'Beauty and skincare products.'],
            ['ar' => 'السوق الشامل', 'en' => 'Mega Market', 'desc_ar' => 'كل شيء تحت سقف واحد.', 'desc_en' => 'Everything under one roof.'],
            ['ar' => 'مكتبة المعرفة', 'en' => 'Knowledge Library', 'desc_ar' => 'كتب ومراجع لطلاب ومثقفين.', 'desc_en' => 'Books and references for learners.'],
        ];
        foreach ($stores as $index => $store) {
            $user = \App\Models\User::skip($index)->first();

            if ($user) {
                Store::create([
                    'name_ar' => $store['ar'],
                    'name_en' => $store['en'],
                    'description_ar' => $store['desc_ar'],
                    'description_en' => $store['desc_en'],
                    'logo' => "stores_logos/store" . ($index + 1) . ".jpg",
                    'status' => 'approved',
                    'rating' => rand(3, 5),
                    'num_of_rate' => rand(50, 300),
                    'is_featured' => rand(0, 1),
                    'user_id' => $user->id, // مستخدم مختلف لكل متجر
                ]);
            }}}
        }



