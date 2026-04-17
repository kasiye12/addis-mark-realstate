<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportPropertiesCommand extends Command
{
    protected $signature = 'properties:import {source? : Source file or URL}';
    protected $description = 'Import properties from external source';

    protected ImageUploadService $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    public function handle()
    {
        $this->info('Starting property import...');
        
        $source = $this->argument('source') ?? storage_path('app/import/properties.csv');
        
        if (!file_exists($source)) {
            $this->error("Source file not found: {$source}");
            return Command::FAILURE;
        }
        
        $this->info("Reading from: {$source}");
        
        $handle = fopen($source, 'r');
        $headers = fgetcsv($handle);
        $count = 0;
        $errors = 0;
        
        $bar = $this->output->createProgressBar();
        
        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = array_combine($headers, $row);
                $this->importProperty($data);
                $count++;
            } catch (\Exception $e) {
                $this->error("Error importing property: {$e->getMessage()}");
                $errors++;
            }
            
            $bar->advance();
        }
        
        fclose($handle);
        $bar->finish();
        
        $this->newLine(2);
        $this->info("Import completed!");
        $this->info("✓ Imported: {$count} properties");
        
        if ($errors > 0) {
            $this->warn("✗ Errors: {$errors} properties failed");
        }
        
        return Command::SUCCESS;
    }

    protected function importProperty(array $data): void
    {
        // Find or create category
        $category = Category::firstOrCreate(
            ['name' => $data['category']],
            ['is_active' => true]
        );
        
        // Find or create location
        $location = Location::firstOrCreate(
            ['area_name' => $data['area']],
            [
                'city' => $data['city'] ?? 'Addis Ababa',
                'is_popular' => false
            ]
        );
        
        // Create property
        $property = Property::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'user_id' => 1, // Admin user
            'price' => $data['price'],
            'price_type' => $data['price_type'] ?? 'sale',
            'property_type' => $data['property_type'] ?? 'apartment',
            'bedrooms' => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'area_sqm' => $data['area_sqm'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => 'available',
            'is_active' => true,
        ]);
        
        // Download and attach images if URL provided
        if (isset($data['image_urls'])) {
            $urls = explode('|', $data['image_urls']);
            foreach ($urls as $index => $url) {
                $this->downloadAndAttachImage($property, $url, $index === 0);
            }
        }
    }

    protected function downloadAndAttachImage(Property $property, string $url, bool $isPrimary = false): void
    {
        try {
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                $filename = Str::uuid() . '.jpg';
                $tempPath = storage_path("app/temp/{$filename}");
                
                Storage::put("temp/{$filename}", $response->body());
                
                // Use the image service to process and store
                // This would need to be adapted based on your exact implementation
                
                Storage::delete("temp/{$filename}");
            }
        } catch (\Exception $e) {
            $this->warn("Failed to download image: {$url}");
        }
    }
}