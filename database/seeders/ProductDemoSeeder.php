<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sku;
use App\Models\AttributeOption;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;

class ProductDemoSeeder extends Seeder
{
    protected $imageService;

    public function __construct()
    {
        $this->imageService = app(ImageService::class);
    }

    public function run(): void
    {
        DB::transaction(function () {
            $startingId = Product::max('id') + 1; // لنبدأ بعد آخر ID
            $count = 10; // عدد المنتجات الجديدة

            foreach (range($startingId, $startingId + $count - 1) as $i) {
                // ✅ أضف السعر مباشرة هنا
                $product = Product::create([
                    'name_ar' => "منتج $i",
                    'name_en' => "Product $i",
                    'description_ar' => "وصف المنتج رقم $i",
                    'description_en' => "Description for product $i",
                    'category_id' => rand(1, 3),
                    'store_id' => 1,
                    'price' => rand(50, 500), // 👈 السعر
                ]);

                // أضف صورتين لكل منتج
                foreach (range(1, 2) as $j) {
                    $path = 'products/main.jpg';
                    $product->images()->create(['image' => $path]);
                }

                // أضف SKUs
                foreach (range(1, 2) as $k) {
                    $sku = $product->skus()->create([
                        'price' => rand(100, 300),
                        'stock' => rand(10, 50),
                    ]);

                    // ربط عشوائي مع خيارات Attributes
                    $attributeOptionIds = AttributeValue::inRandomOrder()->limit(10)->pluck('id');
                    $sku->attributeOptions()->sync($attributeOptionIds);

                    // أضف صور SKU
                    foreach (range(1, 2) as $l) {
                        $path = 'skus/main.jpg';
                        $sku->images()->create(['image' => $path]);
                    }
                }
            }
        });

        $this->command->info('✅ تم إنشاء منتجات جديدة مع أسعار وصور و SKUs بنجاح.');
    }
}
