<?php

namespace App\Services;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use kreait\Firebase\Factory;

class ImageService
{

    /**
     * Create a new class instance.
     */
    protected $disk = 'public';
    public function __construct()
    {

    }

    public function upload($file, $folder = 'uploads'):string
    {
        return $file->store($folder, 'public');
    }

    public function uploadMultiple(array $files, $folder = 'uploads'):array
    {
        return collect($files)->map(fn($file) => $this->upload($file, $folder))->toArray();
    }

    public function delete(string $path):void
    {
        $path = str_replace(url('storage') . '/', '', $path);
        Log::info('before delete'.$path);
        if(Storage::disk($this->disk)->exists($path)){
            Log::info('check delete');
            Storage::disk($this->disk)->delete($path);
            Log::info('after delete');
        }
    }

    public function deleteMultiple(array $paths):void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    public function getUrl(string $path):string
    {
        if($path)
            return url(Storage::url($path));
        else
            return url(Storage::url('default/logo.jpg'));
    }

    public function getUrls(array $paths):array
    {
        return collect($paths)->map(fn($path) => $this->getUrl($path))->toArray();
    }

    public function replace(?string $oldPath, $newFile, string $folder = 'uploads'):string
    {
        if($oldPath){
            $this->delete($oldPath);
        }
        return $this->upload($newFile, $folder);
    }

    public function updateMultiple(array $oldPaths, array $files, string $folder = 'uploads'):array
    {
        $this->deleteMultiple($oldPaths);
        return $this->uploadMultiple($files, $folder);
    }
}
