<?php
namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Store;


class ProductSeeder extends Seeder
{
    public function run(): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Product::truncate(); // ← هذا يحذف كل المتاجر القديمة
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            [
                'name_ar' => 'كتاب تقنيات حديثة',
                'name_en' => 'Modern Tech Book',
                'price' => 28,
                'stock' => 45,
                'rate' => 4.6,
                // 'discount' => 0,
                'description_ar' => 'كتاب عن أحدث تقنيات التكنولوجيا.',
                'description_en' => 'Book about the latest technology trends.',
                // 'details_ar' => 'يشمل معلومات عملية وتطبيقية.',
                //  'details_en' => 'Includes practical and applied information.',
                'unit_ar' => 'نسخة',
                'unit_en' => 'Copy',
                'store_id' => 2,
                'category_id' => 5,
                'status' => 1,
                'num_of_rates' => 40,
                'num_of_purchase' => 50,
                'is_featured' => false,
                'enum' => 'enables',

                'primary_image' => 'products/modern_tech_book/main.jpg',
                'images' => [
                    'products/modern_tech_book/1.jpg',
                    'products/modern_tech_book/2.jpg',
                ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أزرق',
//                        'value_en' => 'Blue',
//                    ],
//                    [
//                        'name_ar' => 'الحجم',
//                        'name_en' => 'Size',
//                        'value_ar' => 'كبير',
//                        'value_en' => 'Large',
//                    ],
//                ],
            ],

            [
                'name_ar' => 'كتاب أطفال',
                'name_en' => 'Children’s Book',
                'price' => 18,
                'stock' => 100,
                'rate' => 4.4,
//                'discount' => 0,
                'description_ar' => 'كتاب قصص مصور للأطفال.',
                'description_en' => 'Illustrated storybook for children.',
                //  'details_ar' => 'مناسب لجميع الأعمار الصغيرة.',
                // 'details_en' => 'Suitable for all young ages.',
                'unit_ar' => 'نسخة',
                'unit_en' => 'Copy',
                'store_id' => 1,
                'category_id' => 1,
                'status' => 1,
                'num_of_rates' => 70,
                'num_of_purchase' => 80,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/children_book/main.jpg',
                'images' => [
        'products/children_book/1.jpg',
        'products/children_book/2.jpg',
    ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'النوع',
//                        'name_en' => 'Type',
//                        'value_ar' => 'قصص',
//                        'value_en' => 'Stories',
//                    ],
//                    [
//                        'name_ar' => 'الصفحة',
//                        'name_en' => 'Pages',
//                        'value_ar' => '150',
//                        'value_en' => '150',
//                    ],
//                ],
            ],
            [
                'name_ar' => 'كتاب تعلم البرمجة',
                'name_en' => 'Programming Learning Book',
                'price' => 35,
                'stock' => 50,
                'rate' => 4.7,
//                'discount' => 5,
            'description_ar' => 'كتاب شامل لتعلم أساسيات البرمجة.',
            'description_en' => 'Comprehensive book for learning programming basics.',
            //    'details_ar' => 'يشمل أمثلة وتمارين عملية.',
            // 'details_en' => 'Includes practical examples and exercises.',
            'unit_ar' => 'نسخة',
            'unit_en' => 'Copy',
            'store_id' => 1,  // عالم التعلم
            'category_id' => 1, // كتب
            'status' => 1,
            'num_of_rates' => 25,
            'num_of_purchase' => 30,
            'is_featured' => true,
            'enum' => 'enables',
            'primary_image' => 'products/programming_book/main.jpg',
            'images' => [
                'products/programming_book/1.jpg',
                'products/programming_book/2.jpg',
            ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'المستوى',
//                        'name_en' => 'Level',
//                        'value_ar' => 'متقدم',
//                        'value_en' => 'Advanced',
//                    ],
//                    [
//                        'name_ar' => 'اللغة',
//                        'name_en' => 'Language',
//                        'value_ar' => 'إنجليزية',
//                        'value_en' => 'English',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'هاتف ذكي جديد',
                'name_en' => 'New Smart Phone',
                'price' => 450,
                'stock' => 25,
                'rate' => 4.9,
//                'discount' => 10,
            'description_ar' => 'هاتف ذكي بمواصفات حديثة وتقنيات متطورة.',
            'description_en' => 'Smartphone with modern specs and advanced technology.',
            //   'details_ar' => 'كاميرا عالية الجودة وبطارية طويلة العمر.',
            //   'details_en' => 'High-quality camera and long-lasting battery.',
            'unit_ar' => 'قطعة',
            'unit_en' => 'Piece',
            'store_id' => 2, // سوق التقنية
            'category_id' => 6, // هواتف
            'status' => 1,
            'num_of_rates' => 45,
            'num_of_purchase' => 60,
            'is_featured' => true,
            'enum' => 'enables',
            'primary_image' => 'products/smartphone/main.jpg',
            'images' => [
                'products/smartphone/1.jpg',
                'products/smartphone/2.jpg',
            ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أسود',
//                        'value_en' => 'Black',
//                    ],
//                    [
//                        'name_ar' => 'السعة',
//                        'name_en' => 'Storage',


//                        'value_ar' => '128 جيجابايت',
//                        'value_en' => '128GB',
//                    ],
//                ],
            ],
            [
                'name_ar' => 'فستان سهرة أنيق',
                'name_en' => 'Elegant Evening Dress',
                'price' => 120,
                'stock' => 40,
                'rate' => 4.3,
//                'discount' => 0,
            'description_ar' => 'فستان سهرة بتصميم عصري يناسب المناسبات الخاصة.',
            'description_en' => 'Trendy evening dress suitable for special occasions.',
            //  'details_ar' => 'مصنوع من أجود أنواع القماش.',
            //  'details_en' => 'Made from high-quality fabric.',
            'unit_ar' => 'قطعة',
            'unit_en' => 'Piece',
            'store_id' => 3, // ركن الموضة
            'category_id' => 1, // ملابس نسائية
            'status' => 1,
            'num_of_rates' => 15,
            'num_of_purchase' => 18,
            'is_featured' => false,
            'enum' => 'enables',
            'primary_image' => 'products/evening_dress/main.jpg',
            'images' => [
                'products/evening_dress/1.jpg',
                'products/evening_dress/2.jpg',
            ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أحمر',
//                        'value_en' => 'Red',
//                    ],
//                    [
//                        'name_ar' => 'المقاس',
//                        'name_en' => 'Size',
//                        'value_ar' => 'متوسط',
//                        'value_en' => 'Medium',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'مخبوزات التمر',
                'name_en' => 'Date Pastries',
                'price' => 12,
                'stock' => 150,
                'rate' => 4.8,
                //               'discount' => 0,
                'description_ar' => 'مخبوزات طازجة مع حشوة التمر اللذيذة.',
                'description_en' => 'Fresh pastries with delicious date filling.',
                //  'details_ar' => 'مصنوعة يدوياً يومياً.',
                //  'details_en' => 'Handmade fresh daily.',
                'unit_ar' => 'علبة',
                'unit_en' => 'Box',
                'store_id' => 4, // مخبوزات الخير
                'category_id' => 6, // حلويات (تأكد من رقم الفئة الصحيح)
                'status' => 1,
                'num_of_rates' => 30,
                'num_of_purchase' => 55,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/date_pastries/main.jpg',
                'images' => [
                    'products/date_pastries/1.jpg',
                    'products/date_pastries/2.jpg',
                ],
            ],
            [
                'name_ar' => 'حاسوب محمول عالي الأداء',
                'name_en' => 'High-Performance Laptop',
                'price' => 800,
                'stock' => 15,
                'rate' => 4.5,
                //              'discount' => 7,
                'description_ar' => 'حاسوب محمول مناسب للألعاب والعمل.',
                'description_en' => 'Laptop suitable for gaming and work.',
                //  'details_ar' => 'معالج سريع وبطاقة رسوميات متقدمة.',
                //  'details_en' => 'Fast processor and advanced graphics card.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 2, // سوق التقنية
                'category_id' => 6, // كمبيوتر
                'status' => 1,
                'num_of_rates' => 50,
                'num_of_purchase' => 40,
                'is_featured' => true,
                'enum' => 'enables',
                'primary_image' => 'products/laptop/main.jpg',
                'images' => [
                    'products/laptop/1.jpg',
                    'products/laptop/2.jpg',


],
//                'attributes' => [
//                    [
//                        'name_ar' => 'المعالج',
//                        'name_en' => 'Processor',
//                        'value_ar' => 'Intel i7',
//                        'value_en' => 'Intel i7',
//                    ],
//                    [
//                        'name_ar' => 'الرام',
//                        'name_en' => 'RAM',
//                        'value_ar' => '16 جيجابايت',
//                        'value_en' => '16GB',
//                    ],
//                ],
            ],
            [
                'name_ar' => 'حقيبة ظهر مدرسية',
                'name_en' => 'School Backpack',
                'price' => 45,
                'stock' => 60,
                'rate' => 4.2,
                //               'discount' => 3,
                'description_ar' => 'حقيبة ظهر متينة وعملية للطلاب.',
                'description_en' => 'Durable and practical backpack for students.',
                //  'details_ar' => 'تحتوي على عدة جيوب لتنظيم الأدوات.',
                //   'details_en' => 'Contains multiple pockets for organizing supplies.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 1, // عالم التعلم
                'category_id' => 5, // حقائب
                'status' => 1,
                'num_of_rates' => 20,
                'num_of_purchase' => 22,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/backpack/main.jpg',
                'images' => [
                    'products/backpack/1.jpg',
                    'products/backpack/2.jpg',
                ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'السعة',
//                        'name_en' => 'Capacity',
//                        'value_ar' => '30 لتر',
//                        'value_en' => '30L',
//                    ],
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'رمادي',
//                        'value_en' => 'Gray',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'ساعة ذكية رياضية',
                'name_en' => 'Smart Sports Watch',
                'price' => 150,
                'stock' => 35,
                'rate' => 4.6,
                //               'discount' => 0,
                'description_ar' => 'ساعة ذكية مع ميزات تتبع النشاط الرياضي.',
                'description_en' => 'Smartwatch with sports activity tracking features.',
                // 'details_ar' => 'مقاومة للماء وعمر بطارية طويل.',
                //   'details_en' => 'Water-resistant with long battery life.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 5, // إلكترونيات بلا حدود
                'category_id' => 2, // ساعات ذكية
                'status' => 1,
                'num_of_rates' => 28,
                'num_of_purchase' => 33,
                'is_featured' => true,
                'enum' => 'enables',
                'primary_image' => 'products/smartwatch/main.jpg',
                'images' => [
                    'products/smartwatch/1.jpg',
                    'products/smartwatch/2.jpg',
                ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أخضر',
//                        'value_en' => 'Green',
//                    ],
//                    [
//                        'name_ar' => 'المقاومة للماء',
//                        'name_en' => 'Water Resistant',
//                        'value_ar' => 'نعم',
//                        'value_en' => 'Yes',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'لعبة تعليمية للأطفال',
                'name_en' => 'Educational Toy for Kids',

'price' => 25,
                'stock' => 90,
                'rate' => 4.4,
  //              'discount' => 0,
                'description_ar' => 'لعبة تساعد على تطوير مهارات الطفل.',
                'description_en' => 'Toy that helps develop children’s skills.',
  //              'details_ar' => 'مصنوعة من مواد آمنة للأطفال.',
    //            'details_en' => 'Made from child-safe materials.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 1, // عالم التعلم
                'category_id' => 3, // ألعاب تعليمية
                'status' => 1,
                'num_of_rates' => 35,
                'num_of_purchase' => 40,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/educational_toy/main.jpg',
                'images' => [
        'products/educational_toy/1.jpg',
        'products/educational_toy/2.jpg',
    ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'العمر',
//                        'name_en' => 'Age',
//                        'value_ar' => '3-6 سنوات',
//                        'value_en' => '3-6 years',
//                    ],
//                    [
//                        'name_ar' => 'المادة',
//                        'name_en' => 'Material',
//                        'value_ar' => 'خشب',
//                        'value_en' => 'Wood',
//                    ],
//                ],
            ],
            [
                'name_ar' => 'ميني ثلاجة صغيرة',
                'name_en' => 'Mini Fridge',
                'price' => 200,
                'stock' => 10,
                'rate' => 4.1,
                //               'discount' => 5,
                'description_ar' => 'ثلاجة صغيرة مناسبة للمكاتب والغرف.',
                'description_en' => 'Small fridge suitable for offices and rooms.',
                //          'details_ar' => 'تصميم مدمج وهادئ.',
                //        'details_en' => 'Compact and quiet design.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 6, // الأجهزة المنزلية
                'category_id' => 2, // أجهزة منزلية
                'status' => 1,
                'num_of_rates' => 18,
                'num_of_purchase' => 20,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/mini_fridge/main.jpg',
                'images' => [
                    'products/mini_fridge/1.jpg',
                    'products/mini_fridge/2.jpg',
                ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'السعة',
//                        'name_en' => 'Capacity',
//                        'value_ar' => '50 لتر',
//                        'value_en' => '50L',
//                    ],
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أبيض',
//                        'value_en' => 'White',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'سماعات رأس لاسلكية',
                'name_en' => 'Wireless Headphones',
                'price' => 85,
                'stock' => 45,
                'rate' => 4.5,
                //   'discount' => 7,
                'description_ar' => 'سماعات لاسلكية بجودة صوت عالية.',
                'description_en' => 'Wireless headphones with high sound quality.',
//                'details_ar' => 'تدعم البلوتوث وعمر البطارية طويل.',
            //              'details_en' => 'Supports Bluetooth and has long battery life.',
            'unit_ar' => 'قطعة',
            'unit_en' => 'Piece',
            'store_id' => 5, // إلكترونيات بلا حدود
            'category_id' => 1, // سماعات
            'status' => 1,
            'num_of_rates' => 40,
            'num_of_purchase' => 50,
            'is_featured' => true,
'enum' => 'enables',
                'primary_image' => 'products/headphones/main.jpg',
                'images' => [
        'products/headphones/1.jpg',
        'products/headphones/2.jpg',
    ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'نوع الاتصال',
//                        'name_en' => 'Connection Type',
//                        'value_ar' => 'بلوتوث',
//                        'value_en' => 'Bluetooth',
//                    ],
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أسود',
//                        'value_en' => 'Black',
//                    ],
//                ],
            ],

            [
                'name_ar' => 'كتاب قصص الأطفال',
                'name_en' => 'Children’s Story Book',
                'price' => 20,
                'stock' => 80,
                'rate' => 4.6,
                //               'discount' => 0,
                'description_ar' => 'كتاب يحتوي على قصص ممتعة للأطفال.',
                'description_en' => 'Book containing fun stories for kids.',
                //            'details_ar' => 'رسومات ملونة وجذابة.',
                //          'details_en' => 'Colorful and attractive illustrations.',
                'unit_ar' => 'نسخة',
                'unit_en' => 'Copy',
                'store_id' => 1, // عالم التعلم
                'category_id' => 1, // كتب
                'status' => 1,
                'num_of_rates' => 22,
                'num_of_purchase' => 28,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/children_story_book/main.jpg',
                'images' => [
                    'products/children_story_book/1.jpg',
                    'products/children_story_book/2.jpg',
                ],
            ],
            [
                'name_ar' => 'كاميرا رقمية احترافية',
                'name_en' => 'Professional Digital Camera',
                'price' => 650,
                'stock' => 18,
                'rate' => 4.8,
//                'discount' => 5,
            'description_ar' => 'كاميرا رقمية بجودة عالية للمصورين المحترفين.',
            'description_en' => 'High-quality digital camera for professional photographers.',
            //             'details_ar' => 'دقة تصوير عالية وعدسات متعددة.',
            //           'details_en' => 'High resolution and multiple lenses.',
            'unit_ar' => 'قطعة',
            'unit_en' => 'Piece',
            'store_id' => 5, // إلكترونيات بلا حدود
            'category_id' => 5, // كاميرات
            'status' => 1,
            'num_of_rates' => 30,
            'num_of_purchase' => 25,
            'is_featured' => true,
            'enum' => 'enables',
            'primary_image' => 'products/digital_camera/main.jpg',
            'images' => [
                'products/digital_camera/1.jpg',
                'products/digital_camera/2.jpg',
            ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'الدقة',
//                        'name_en' => 'Resolution',
//                        'value_ar' => '24 ميجابكسل',
//                        'value_en' => '24MP',
//                    ],
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أسود',
//                        'value_en' => 'Black',
//                    ],
//                ],
        ],
            [
                'name_ar' => 'روبوت تعليمي',
                'name_en' => 'Educational Robot',
                'price' => 90,
                'stock' => 40,
                'rate' => 4.4,
                //               'discount' => 0,
                'description_ar' => 'روبوت تفاعلي لتعليم الأطفال البرمجة.',
                'description_en' => 'Interactive robot for teaching kids programming.',

//                'details_ar' => 'سهل الاستخدام مع تطبيق مرفق.',
  //              'details_en' => 'Easy to use with an accompanying app.',
                'unit_ar' => 'قطعة',
                'unit_en' => 'Piece',
                'store_id' => 1, // عالم التعلم
                'category_id' => 3, // ألعاب تعليمية
                'status' => 1,
                'num_of_rates' => 25,
                'num_of_purchase' => 30,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/educational_robot/main.jpg',
                'images' => [
        'products/educational_robot/1.jpg',
        'products/educational_robot/2.jpg',
    ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'الوظيفة',
//                        'name_en' => 'Function',
//                        'value_ar' => 'تعليم البرمجة',
//                        'value_en' => 'Programming Education',
//                    ],
//                    [
//                        'name_ar' => 'اللون',
//                        'name_en' => 'Color',
//                        'value_ar' => 'أبيض',
//                        'value_en' => 'White',
//                    ],
//                ],
            ],
            [
                'name_ar' => 'مجموعة أدوات مكياج',
                'name_en' => 'Makeup Kit Set',
                'price' => 75,
                'stock' => 70,
                'rate' => 4.3,
                //               'discount' => 0,
                'description_ar' => 'مجموعة مكياج متكاملة لكل المناسبات.',
                'description_en' => 'Complete makeup kit for all occasions.',
                //              'details_ar' => 'تشمل ظلال وأحمر شفاه وأدوات تطبيق.',
                //               'details_en' => 'Includes eyeshadows, lipsticks, and application tools.',
                'unit_ar' => 'مجموعة',
                'unit_en' => 'Set',
                'store_id' => 3, // ركن الموضة
                'category_id' => 3, // مستحضرات تجميل
                'status' => 1,
                'num_of_rates' => 18,
                'num_of_purchase' => 22,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/makeup_kit/main.jpg',
                'images' => [
                    'products/makeup_kit/1.jpg',
                    'products/makeup_kit/2.jpg',
                ],
            ],
            [
                'name_ar' => 'مجموعة أدوات طبخ',
                'name_en' => 'Cooking Utensil Set',
                'price' => 55,
                'stock' => 50,
                'rate' => 4.5,
                //               'discount' => 5,
                'description_ar' => 'مجموعة أدوات مطبخ عملية وعالية الجودة.',
                'description_en' => 'Practical and high-quality kitchen utensils set.',
                //             'details_ar' => 'مصنوعة من مواد مقاومة للصدأ.',
                //             'details_en' => 'Made from rust-resistant materials.',
                'unit_ar' => 'مجموعة',
                'unit_en' => 'Set',
                'store_id' => 6, // الأجهزة المنزلية
                'category_id' => 2, // أجهزة منزلية
                'status' => 1,
                'num_of_rates' => 20,
                'num_of_purchase' => 25,
                'is_featured' => false,
                'enum' => 'enables',
                'primary_image' => 'products/cooking_utensils/main.jpg',
                'images' => [
                    'products/cooking_utensils/1.jpg',
                    'products/cooking_utensils/2.jpg',
                ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'المادة',
//                        'name_en' => 'Material',
//                        'value_ar' => 'ستانلس ستيل',
//                        'value_en' => 'Stainless Steel',
//                    ],
//                ],
        ],


[
    'name_ar' => 'ساعة ذكية',
    'name_en' => 'Smart Watch',
    'price' => 120,
    'stock' => 35,
    'rate' => 4.7,
    //              'discount' => 10,
    'description_ar' => 'ساعة ذكية مع ميزات متعددة.',
    'description_en' => 'Smart watch with multiple features.',
    //              'details_ar' => 'تدعم البلوتوث وتتبع اللياقة.',
    //              'details_en' => 'Supports Bluetooth and fitness tracking.',
    'unit_ar' => 'قطعة',
    'unit_en' => 'Piece',
    'store_id' => 5,
    'category_id' => 1,
    'status' => 1,
    'num_of_rates' => 50,
    'num_of_purchase' => 45,
    'is_featured' => true,
    'enum' => 'enables',
    'primary_image' => 'products/smart_watch/main.jpg',
    'images' => [
        'products/smart_watch/1.jpg',
        'products/smart_watch/2.jpg',
    ],
//                'attributes' => [
//                    [
//                        'name_ar' => 'المقاومة للماء',
//                        'name_en' => 'Water Resistant',
//                        'value_ar' => 'نعم',
//                        'value_en' => 'Yes',
//                    ],
//                ],
        ],
        ];
        foreach ($products as $productData) {

//            $attributes = $productData['attributes'] ?? [];
//            unset($productData['attributes']);

            $images = $productData['images'] ?? [];
            unset($productData['images']);

            $primaryImage = $productData['primary_image'] ?? null;
            unset($productData['primary_image']);


            $product = Product::create($productData);


            if ($primaryImage) {
                $product->images()->create([
                    'url_image' => $primaryImage,
                    'is_primary' => true,
                ]);
            }


            foreach ($images as $img) {
                $product->images()->create([
                    'url_image' => $img,
                    'is_primary' => false,
                ]);
            }


//            foreach ($attributes as $attrData) {
//                // أولاً إنشاء الخاصية إذا مش موجودة
//                $attribute = Attribute::create([
//                    'name_en' => $attrData['name_en'],
//                    'name_ar' => $attrData['name_ar'],
//                ]);
//
//                // ثم ربط الخاصية مع المنتج مع القيم (value_en, value_ar) في جدول attribute_values
//                $product->attributes()->attach($attribute->id, [
//                    'value_en' => $attrData['value_en'],
//                    'value_ar' => $attrData['value_ar'],
//                ]);
//            }


        }
   }
}
