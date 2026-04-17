<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'image', 'is_active'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function getActivePropertiesCountAttribute()
    {
        return $this->properties()->where('is_active', true)->count();
    }
    
}