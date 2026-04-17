<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title', 'slug', 'description', 'short_description', 'location',
        'category_id', 'status', 'featured_image', 'gallery',
        'starting_price', 'start_date', 'completion_date', 'developer',
        'amenities', 'specifications', 'is_featured', 'is_active', 'views'
    ];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'specifications' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
        'starting_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image && \Storage::disk('public')->exists($this->featured_image)) {
            return route('file.show', ['path' => $this->featured_image]);
        }
        return asset('images/default-project.jpg');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'ongoing' => ['bg-blue-100 text-blue-800', 'Ongoing'],
            'completed' => ['bg-green-100 text-green-800', 'Completed'],
            'upcoming' => ['bg-yellow-100 text-yellow-800', 'Upcoming'],
            default => ['bg-gray-100 text-gray-800', 'Unknown'],
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}