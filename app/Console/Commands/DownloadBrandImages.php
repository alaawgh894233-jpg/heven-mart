<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadBrandImages extends Command
{
    protected $signature = 'download:brand-images';

    protected $description = 'Download 100 brand images and store them in storage/app/public/brands/';

    public function handle()
    {
        $this->info("🚀 Starting image download...");

        $brands = [
            'nike', 'adidas', 'samsung', 'apple', 'huawei', 'xiaomi', 'loreal', 'bourjois', 'toyota', 'honda',
            'coca_cola', 'pepsi', 'louis_vuitton', 'gucci', 'prada', 'casio', 'realme', 'panasonic', 'dell', 'hp',
            'sony', 'lenovo', 'microsoft', 'amazon', 'google', 'philips', 'bosch', 'lg', 'nestle', 'nissan',
            'puma', 'reebok', 'oppo', 'vivo', 'uniqlo', 'zara', 'h&m', 'gap', 'asics', 'new_balance',
            'fossil', 'rolex', 'citizen', 'tag_heuer', 'seiko', 'tissot', 'omega', 'samsung_watch', 'sennheiser', 'beats',
            'jbl', 'anker', 'canon', 'nikon', 'fujifilm', 'go_pro', 'dyson', 'electrolux', 'beko', 'tefal',
            'whirlpool', 'sharp', 'acer', 'asus', 'alienware', 'msi', 'razer', 'steelseries', 'logitech', 'corsair',
            'kingston', 'sandisk', 'wd', 'seagate', 'transcend', 'intel', 'amd', 'nvidia', 'palit', 'gigabyte',
            'biostar', 'foxconn', 'cooler_master', 'thermaltake', 'nzxt', 'lian_li', 'phanteks', 'evga', 'zotac', 'sapphire',
            'benq', 'viewsonic', 'hisense', 'tcl', 'skyworth', 'haier', 'daikin', 'carrier', 'gree', 'midea'
        ];

        $baseUrl = 'https://via.placeholder.com/300x300.png?text='; // placeholder service
        $disk = Storage::disk('public');

        if (!$disk->exists('brands')) {
            $disk->makeDirectory('brands');
        }

        foreach ($brands as $brand) {
            $filename = $brand . '.png';
            $url = $baseUrl . urlencode(Str::title(str_replace('_', ' ', $brand)));

            $contents = file_get_contents($url);
            if ($contents) {
                $disk->put("brands/{$filename}", $contents);
                $this->info("✅ Saved: brands/{$filename}");
            } else {
                $this->error("❌ Failed to fetch: $filename");
            }
        }

        $this->info("🎉 Download complete! All images are in: storage/app/public/brands/");
    }
}
