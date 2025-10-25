<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadStoreImages extends Command
{
    protected $signature = 'stores:download-images';
    protected $description = 'Download store logos from Unsplash and save them locally';

    public function handle()
    {

        $imageUrls = [
            'https://images.unsplash.com/photo-1586190848861-99aa4a171e90', // عالم التعلم
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff',    // سوق التقنية
            'https://images.unsplash.com/photo-1556740749-887f6717d7e4',    // ركن الموضة
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38', // مخبوزات الخير
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb', // إلكترونيات بلا حدود
            'https://images.unsplash.com/photo-1494526585095-c41746248156', // الكتاب الذهبي
            'https://images.unsplash.com/photo-1519125323398-675f0ddb6308', // عالم الألعاب
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d', // قهوة الصباح
            'https://images.unsplash.com/photo-1504386106331-3e4e71712b38', // أساسيات المنزل
            'https://images.unsplash.com/photo-1497493292307-31c376b6e479', // مستلزمات الطفل
            'https://images.unsplash.com/photo-1501594907352-04cda38ebc29', // سوق الطبيعة
            'https://images.unsplash.com/photo-1454165205744-3b78555e5572', // الرياضة للجميع
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f', // الموضة اليومية
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30', // أجهزة المستقبل
            'https://images.unsplash.com/photo-1492724441997-5dc865305da7', // عطور الشرق
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6', // أدوات المدرسة
            'https://images.unsplash.com/photo-1515377905703-c4788e51af15', // مملكة الهواتف
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f', // جمال وأناقة
            'https://images.unsplash.com/photo-1503602642458-232111445657', // السوق الشامل
            'https://images.unsplash.com/photo-1472214103451-9374bd1c798e', // مكتبة المعرفة
        ];

        $folder = 'stores_logos';

        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }



        foreach ($imageUrls as $index => $url) {
            try {
                $response = Http::get($url . '?w=400&h=400&fit=crop'); // optional resizing
                if ($response->ok()) {
                    $filename = "store" . ($index + 1) . ".jpg";
                    Storage::disk('public')->put($folder . '/' . $filename, $response->body());

                    $this->info("Downloaded: $filename");
                } else {
                    $this->error("Failed to download: $url");
                }
            } catch (\Exception $e) {
                $this->error("Error with $url: " . $e->getMessage());
            }
        }

//        $this->info('✅ Done downloading images.');
    }

}
