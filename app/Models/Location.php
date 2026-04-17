<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
        'is_popular',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_popular' => 'boolean',
    ];

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

    public function getTotalPropertiesCountAttribute()
    {
        return $this->properties()->count();
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeWithActiveProperties($query)
    {
        return $query->whereHas('properties', function($q) {
            $q->where('is_active', true);
        });
    }
}