<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Storage;

class Location extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'city',
        'sub_city',
        'area_name',
        'slug',
        'latitude',
        'longitude',
        'image',
        'is_popular',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_popular' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('area_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->area_name,
            $this->sub_city,
            $this->city
        ]));
    }

    public function getActivePropertiesCountAttribute()
    {
        return $this->properties()->where('is_active', true)->count();
    }

    /**
     * Get the location image URL with cache busting
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            // Add timestamp to prevent caching
            return route('file.show', ['path' => $this->image]) . '?v=' . time();
        }
        return null;
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }
}