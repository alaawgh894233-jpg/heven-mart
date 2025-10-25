<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadProductImages extends Command
{
    protected $signature = 'products:download-images';
    protected $description = 'Download product images from Unsplash and save them locally';

    public function handle()
    {
        $imagesMap = [
            'modern_tech_book' => [
                'https://images.unsplash.com/photo-1553729459-efe14ef6055d',
                'https://images.unsplash.com/photo-1581090700227-1e37b190418e',
                'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f',
            ],
            'children_book' => [
                'https://images.unsplash.com/photo-1604147706288-87bed55b4e9b',
                'https://images.unsplash.com/photo-1589820296159-217c6b60f9b7',
                'https://images.unsplash.com/photo-1617059322000-e9e79a3122a2',
            ],
            'programming_book' => [
                'https://images.unsplash.com/photo-1517433456452-f9633a875f6f',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f',
                'https://images.unsplash.com/photo-1525186402429-27d97af1a1db',
            ],
            'smartphone' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e',
            ],
            'evening_dress' => [
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
                'https://images.unsplash.com/photo-1495121605193-b116b5b09a4c',
                'https://images.unsplash.com/photo-1500917293891-ef795e70e1f6',
            ],
            'date_pastries' => [
                'https://images.unsplash.com/photo-1527515637460-5626d35a8a21',
                'https://images.unsplash.com/photo-1542834369-f10ebf06d3cb',
                'https://images.unsplash.com/photo-1523983303006-6173e27c9b36',
            ],
            'laptop' => [
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
            ],
            'backpack' => [
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
                'https://images.unsplash.com/photo-1520975691974-1b370ca602b9',
                'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91',
            ],
            'sport_watch' => [
                'https://images.unsplash.com/photo-1517433456452-f9633a875f6f',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f',
                'https://images.unsplash.com/photo-1525186402429-27d97af1a1db',
            ],
            'educational_toy' => [
                'https://images.unsplash.com/photo-1527515637460-5626d35a8a21',
                'https://images.unsplash.com/photo-1542834369-f10ebf06d3cb',
                'https://images.unsplash.com/photo-1523983303006-6173e27c9b36',
            ],
            'mini_fridge' => [
                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
            ],
            'wireless_headphones' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e',
            ],
            'children_story_book' => [
                'https://images.unsplash.com/photo-1604147706288-87bed55b4e9b',
                'https://images.unsplash.com/photo-1589820296159-217c6b60f9b7',
                'https://images.unsplash.com/photo-1617059322000-e9e79a3122a2',
            ],
            'digital_camera' => [
                'https://images.unsplash.com/photo-1517433456452-f9633a875f6f',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f',
                'https://images.unsplash.com/photo-1525186402429-27d97af1a1db',
            ],
            'educational_robot' => [
                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
            ],
            'makeup_kit' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e',
            ],
            'cooking_set' => [
                'https://images.unsplash.com/photo-1517433456452-f9633a875f6f',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f',
                'https://images.unsplash.com/photo-1525186402429-27d97af1a1db',
            ],
            'smartwatch' => [
                'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
            ],
        ];

        $folder = 'products';

        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        foreach ($imagesMap as $productKey => $urls) {
            $productFolder = $folder . '/' . $productKey;

            if (!Storage::disk('public')->exists($productFolder)) {
                Storage::disk('public')->makeDirectory($productFolder);
            }

            foreach ($urls as $index => $url) {
                try {
                    $filename = $index === 0 ? 'main.jpg' : ($index . '.jpg');
                    $path = $productFolder . '/' . $filename;
                    $response = Http::get($url . '?w=400&h=400&fit=crop');

                    if ($response->ok()) {
                        Storage::disk('public')->put($path, $response->body());
                        $this->info("✅ Downloaded: $path");
                    } else {
                        $this->error("❌ Failed to download: $url");
                    }
                } catch (\Exception $e) {
                    $this->error("⚠️ Error downloading $url: " . $e->getMessage());
                }
            }
        }

        $this->info(' All product images downloaded successfully.');
    }
}
