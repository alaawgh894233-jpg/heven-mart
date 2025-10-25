<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $attributes = [
            [
                'name_en' => 'Color',
                'name_ar' => 'اللون',
                'options' => [
                    ['name_en' => 'Red', 'name_ar' => 'أحمر', 'color_code' => '#FF0000'],
                    ['name_en' => 'Blue', 'name_ar' => 'أزرق', 'color_code' => '#0000FF'],
                    ['name_en' => 'Green', 'name_ar' => 'أخضر', 'color_code' => '#00FF00'],
                    ['name_en' => 'Black', 'name_ar' => 'أسود', 'color_code' => '#000000'],
                ],
            ],
            [
                'name_en' => 'Size',
                'name_ar' => 'الحجم',
                'options' => [
                    ['name_en' => 'Small', 'name_ar' => 'صغير'],
                    ['name_en' => 'Medium', 'name_ar' => 'متوسط'],
                    ['name_en' => 'Large', 'name_ar' => 'كبير'],
                    ['name_en' => 'XL', 'name_ar' => 'إكس لارج'],
                ],
            ],
            [
                'name_en' => 'Material',
                'name_ar' => 'المادة',
                'options' => [
                    ['name_en' => 'Cotton', 'name_ar' => 'قطن'],
                    ['name_en' => 'Polyester', 'name_ar' => 'بوليستر'],
                    ['name_en' => 'Wool', 'name_ar' => 'صوف'],
                    ['name_en' => 'Silk', 'name_ar' => 'حرير'],
                ],
            ],
            [
                'name_en' => 'Style',
                'name_ar' => 'النمط',
                'options' => [
                    ['name_en' => 'Casual', 'name_ar' => 'كاجوال'],
                    ['name_en' => 'Formal', 'name_ar' => 'رسمي'],
                    ['name_en' => 'Sport', 'name_ar' => 'رياضي'],
                    ['name_en' => 'Classic', 'name_ar' => 'كلاسيكي'],
                ],
            ],
            [
                'name_en' => 'Length',
                'name_ar' => 'الطول',
                'options' => [
                    ['name_en' => 'Short', 'name_ar' => 'قصير'],
                    ['name_en' => 'Regular', 'name_ar' => 'عادي'],
                    ['name_en' => 'Long', 'name_ar' => 'طويل'],
                    ['name_en' => 'Extra Long', 'name_ar' => 'طويل جدًا'],
                ],
            ],
            [
                'name_en' => 'Fit',
                'name_ar' => 'الملاءمة',
                'options' => [
                    ['name_en' => 'Slim', 'name_ar' => 'ضيق'],
                    ['name_en' => 'Regular', 'name_ar' => 'منتظم'],
                    ['name_en' => 'Loose', 'name_ar' => 'واسع'],
                    ['name_en' => 'Oversize', 'name_ar' => 'فضفاض'],
                ],
            ],
            [
                'name_en' => 'Pattern',
                'name_ar' => 'النقشة',
                'options' => [
                    ['name_en' => 'Plain', 'name_ar' => 'سادة'],
                    ['name_en' => 'Striped', 'name_ar' => 'مخطط'],
                    ['name_en' => 'Checked', 'name_ar' => 'مربعات'],
                    ['name_en' => 'Printed', 'name_ar' => 'مطبوع'],
                ],
            ],
            [
                'name_en' => 'Finish',
                'name_ar' => 'اللمسة',
                'options' => [
                    ['name_en' => 'Matte', 'name_ar' => 'غير لامع'],
                    ['name_en' => 'Glossy', 'name_ar' => 'لامع'],
                    ['name_en' => 'Satin', 'name_ar' => 'ساتان'],
                    ['name_en' => 'Textured', 'name_ar' => 'ملمس'],
                ],
            ],
            [
                'name_en' => 'Neckline',
                'name_ar' => 'الياقة',
                'options' => [
                    ['name_en' => 'Round', 'name_ar' => 'دائري'],
                    ['name_en' => 'V-Neck', 'name_ar' => 'رقبة V'],
                    ['name_en' => 'Collared', 'name_ar' => 'ياقة'],
                    ['name_en' => 'Turtleneck', 'name_ar' => 'رقبة عالية'],
                ],
            ],
            [
                'name_en' => 'Sleeve',
                'name_ar' => 'الكم',
                'options' => [
                    ['name_en' => 'Short', 'name_ar' => 'قصير'],
                    ['name_en' => 'Long', 'name_ar' => 'طويل'],
                    ['name_en' => 'Sleeveless', 'name_ar' => 'بدون أكمام'],
                    ['name_en' => '3/4 Length', 'name_ar' => 'طول ٣/٤'],
                ],
            ],
        ];

        foreach ($attributes as $attr) {
            $attribute = Attribute::create([
                'name_en' => $attr['name_en'],
                'name_ar' => $attr['name_ar'],
            ]);

            foreach ($attr['options'] as $option) {
                AttributeOption::create([
                    'attribute_id' => $attribute->id,
                    'name_en' => $option['name_en'],
                    'name_ar' => $option['name_ar'],
                    'color_code' => $option['color_code'] ?? null,
                ]);
            }
        }
    }
}
