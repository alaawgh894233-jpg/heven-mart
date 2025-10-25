<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadCategoryImages extends Command
{
    protected $signature = 'categories:download-images';
    protected $description = 'Download category images from Unsplash and save them locally';

    public function handle()
    {
        $imageUrls = [
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=400&fit=crop', // كتب
            'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?w=400&h=400&fit=crop', // قرطاسية
            'https://images.unsplash.com/photo-1581092334319-06b0e4961a2a?w=400&h=400&fit=crop', // ألعاب تعليمية
            'https://images.unsplash.com/photo-1556740749-887f6717d7e4?w=400&h=400&fit=crop', // أجهزة مدرسية
            'https://images.unsplash.com/photo-1520962915203-23831b0729f2?w=400&h=400&fit=crop', // حقائب
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop', // كمبيوتر
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop', // هواتف
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=400&h=400&fit=crop', // إكسسوارات
            'https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=400&h=400&fit=crop', // ألعاب إلكترونية
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=400&h=400&fit=crop', // برمجيات
            'https://images.unsplash.com/photo-1520975691907-f96c43c93b96?w=400&h=400&fit=crop', // ملابس نسائية
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=400&fit=crop', // ملابس رجالية
            'https://images.unsplash.com/photo-1514996937319-344454492b37?w=400&h=400&fit=crop', // أحذية
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400&h=400&fit=crop', // ملابس أطفال
            'https://images.unsplash.com/photo-1492724441997-5dc865305da7?w=400&h=400&fit=crop', // ألعاب أطفال
            'https://images.unsplash.com/photo-1504386106331-3e4e71712b38?w=400&h=400&fit=crop', // عربات
            'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=400&h=400&fit=crop', // مستلزمات المواليد
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=400&h=400&fit=crop', // ساعات ذكية
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&h=400&fit=crop', // سماعات
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=400&fit=crop', // أجهزة تتبع

                'https://images.unsplash.com/photo-1542831371-d531d36971e6?w=400&h=400&fit=crop', // عروض يومية
                'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400&h=400&fit=crop', // منتجات منزلية
                'https://images.unsplash.com/photo-1510552776732-03e61cf4b144?w=400&h=400&fit=crop', // إلكترونيات
                'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=400&fit=crop', // ملابس

                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop', // أغذية
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=400&h=400&fit=crop', // مشروبات
                'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=400&fit=crop', // منتجات عضوية
                'https://images.unsplash.com/photo-1542038784456-68724e10d45f?w=400&h=400&fit=crop', // معلبات

                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=400&h=400&fit=crop', // أجهزة منزلية
                'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?w=400&h=400&fit=crop', // تلفزيونات
                'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=400&h=400&fit=crop', // مكيفات
                'https://images.unsplash.com/photo-1503602642458-232111445657?w=400&h=400&fit=crop', // كاميرات

                'https://images.unsplash.com/photo-1521334884684-d80222895322?w=400&h=400&fit=crop', // ملابس
                'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&h=400&fit=crop', // حقائب
                'https://images.unsplash.com/photo-1490367532201-b9bc1dc483f6?w=400&h=400&fit=crop', // ساعات
                'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?w=400&h=400&fit=crop', // نظارات

                // cat39–42
                'https://images.unsplash.com/photo-1581091622370-3c7af7d15bee?w=400&h=400&fit=crop', // كتب علمية
                'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=400&h=400&fit=crop', // روايات
                'https://images.unsplash.com/photo-1528207776546-365bb710ee93?w=400&h=400&fit=crop', // قصص أطفال
                'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=400&h=400&fit=crop', // أدوات مكتبية

                // cat43–46
                'https://images.unsplash.com/photo-1560185127-6b78081c2d6c?w=400&h=400&fit=crop', // أثاث
                'https://images.unsplash.com/photo-1556911220-e15b30f39e41?w=400&h=400&fit=crop', // مطبخ
                'https://images.unsplash.com/photo-1586201375761-83865001e0b9?w=400&h=400&fit=crop', // حمام
                'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=400&h=400&fit=crop', // ديكور

                // cat47–50
                'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=400&h=400&fit=crop', // مستحضرات تجميل
                'https://images.unsplash.com/photo-1495110702113-258c385c2e12?w=400&h=400&fit=crop', // عطور
                'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop', // منتجات شعر
                'https://images.unsplash.com/photo-1542068829-1115f7259450?w=400&h=400&fit=crop', // عناية بالبشرة

                // cat51–54
                'https://images.unsplash.com/photo-1571019613914-85f342c0e085?w=400&h=400&fit=crop', // معدات رياضية
                'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=400&h=400&fit=crop', // ملابس رياضية
                'https://images.unsplash.com/photo-1514996937319-344454492b37?w=400&h=400&fit=crop', // أحذية رياضية
                'https://images.unsplash.com/photo-1554284126-703dd7035ed4?w=400&h=400&fit=crop', // مكملات غذائية

                // cat55–58
                'https://images.unsplash.com/photo-1499703613191-1296c58593c5?w=400&h=400&fit=crop', // ألعاب
                'https://images.unsplash.com/photo-1493666438817-866a91353ca9?w=400&h=400&fit=crop', // هدايا
                'https://images.unsplash.com/photo-1508479382330-611d9f3e30a5?w=400&h=400&fit=crop', // بطاقات معايدة
                'https://images.unsplash.com/photo-1532634726-8b9fb99825aa?w=400&h=400&fit=crop', // ديكورات

                // cat59–62
                'https://images.unsplash.com/photo-1511268550001-e91bfa48f4d0?w=400&h=400&fit=crop', // حقائب سفر
                'https://images.unsplash.com/photo-1505761671935-60b3a7427bad?w=400&h=400&fit=crop', // معدات تخييم
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=400&h=400&fit=crop', // خرائط وأدلة
                'https://images.unsplash.com/photo-1472289065668-ce650ac443d2?w=400&h=400&fit=crop', // أجهزة ملاحة

                // cat63–66
                'https://images.unsplash.com/photo-1504198458649-3128b932f49f?w=400&h=400&fit=crop', // أقلام
                'https://images.unsplash.com/photo-1501747315-124a0eaca060?w=400&h=400&fit=crop', // دفاتر
                'https://images.unsplash.com/photo-1470311322370-b1b58d923a19?w=400&h=400&fit=crop', // ملفات
                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=400&h=400&fit=crop', // مستلزمات أخرى

                // cat67–70
                'https://images.unsplash.com/photo-1512238701577-f182d9ef8af7?w=400&h=400&fit=crop', // خضروات وفواكه
                'https://images.unsplash.com/photo-1505250469679-203ad9ced0cb?w=400&h=400&fit=crop', // حلويات
                'https://images.unsplash.com/photo-1502741126161-b048400d75c9?w=400&h=400&fit=crop', // منتجات الألبان
                'https://images.unsplash.com/photo-1528825871115-3581a5387919?w=400&h=400&fit=crop', // لحوم

                // cat71–74
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop', // حاسبات
                'https://images.unsplash.com/photo-1512499617640-c2f9992431f0?w=400&h=400&fit=crop', // هواتف ذكية
                'https://images.unsplash.com/photo-1503602642458-232111445657?w=400&h=400&fit=crop', // أجهزة تابلت
                'https://images.unsplash.com/photo-1516772672558-fcb116140ec0?w=400&h=400&fit=crop', // سماعات رأس

                // cat75–78
                'https://images.unsplash.com/photo-1522337660859-02fbefca4702?w=400&h=400&fit=crop', // أدوية
                'https://images.unsplash.com/photo-1582719478260-50ac9e8bce45?w=400&h=400&fit=crop', // مكملات
                'https://images.unsplash.com/photo-1580281657525-47aad6b3e907?w=400&h=400&fit=crop', // معدات طبية
                'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400&h=400&fit=crop', // منتجات طبيعية

                // cat79–82
                'https://images.unsplash.com/photo-1540575460516-9c9adb77d5c4?w=400&h=400&fit=crop', // منتجات منزلية
                'https://images.unsplash.com/photo-1510557880182-3a935d84080a?w=400&h=400&fit=crop', // إلكترونيات
                'https://images.unsplash.com/photo-1520975691907-f96c43c93b96?w=400&h=400&fit=crop', // ملابس
                'https://images.unsplash.com/photo-1493666438817-866a91353ca9?w=400&h=400&fit=crop', // ألعاب



        ];


        $folder = 'categories';

        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        foreach ($imageUrls as $index => $url) {
            try {
                $response = Http::get($url . '?w=400&h=400&fit=crop');
                if ($response->ok()) {
                    $filename = "cat" . ($index + 1) . ".jpg";
                    $filePath = $folder . DIRECTORY_SEPARATOR . $filename;
                    Storage::disk('public')->put($folder . '/' . $filename, $response->body());
                    $this->info("Downloaded: $filename");
                } else {
                    $this->error("Failed to download: $url");
                }
            } catch (\Exception $e) {
                $this->error("Error with $url: " . $e->getMessage());
            }
        }
    }
}
