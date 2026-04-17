<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    protected string $disk = 'public';
    protected array $sizes = [
        'thumbnail' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
    ];

    public function uploadPropertyImage(UploadedFile $file, int $propertyId): array
    {
        $filename = $this->generateFilename($file);
        $paths = [];
        
        // Upload original
        $originalPath = $file->storeAs(
            "properties/{$propertyId}",
            "original_{$filename}",
            $this->disk
        );
        $paths['original'] = $originalPath;
        
        // Create different sizes
        foreach ($this->sizes as $size => [$width, $height]) {
            $image = Image::read($file);
            $image->cover($width, $height);
            
            $sizePath = "properties/{$propertyId}/{$size}_{$filename}";
            $fullPath = storage_path("app/public/{$sizePath}");
            
            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->save($fullPath);
            $paths[$size] = $sizePath;
        }
        
        return $paths;
    }

    public function uploadTeamMemberImage(UploadedFile $file): string
    {
        $filename = $this->generateFilename($file);
        $path = "team/{$filename}";
        
        $image = Image::read($file);
        $image->cover(400, 400);
        
        $fullPath = storage_path("app/public/{$path}");
        $directory = dirname($fullPath);
        
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $image->save($fullPath);
        
        return $path;
    }

    public function uploadTestimonialImage(UploadedFile $file): string
    {
        $filename = $this->generateFilename($file);
        $path = "testimonials/{$filename}";
        
        $image = Image::read($file);
        $image->cover(200, 200);
        
        $fullPath = storage_path("app/public/{$path}");
        $directory = dirname($fullPath);
        
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $image->save($fullPath);
        
        return $path;
    }

    public function deletePropertyImages(int $propertyId): void
    {
        $directory = storage_path("app/public/properties/{$propertyId}");
        if (file_exists($directory)) {
            $this->deleteDirectory($directory);
        }
    }

    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    protected function deleteDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }

    public function getImageUrl(?string $path): string
    {
        if (!$path) {
            return asset('images/placeholder.jpg');
        }
        
        return asset('storage/' . $path);
    }
}