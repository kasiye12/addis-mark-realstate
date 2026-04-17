<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BlogPost extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category_id', 'author_id', 'tags', 'post_type', 'reading_time',
        'is_featured', 'is_published', 'published_at', 'views',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image && \Storage::disk('public')->exists($this->featured_image)) {
            return route('file.show', ['path' => $this->featured_image]);
        }
        return asset('images/default-blog.jpg');
    }

    public function getPostTypeBadgeAttribute()
    {
        return match($this->post_type) {
            'blog' => ['bg-purple-100 text-purple-800', 'Blog'],
            'tip' => ['bg-green-100 text-green-800', 'Real Estate Tip'],
            'market_update' => ['bg-blue-100 text-blue-800', 'Market Update'],
            'investment' => ['bg-amber-100 text-amber-800', 'Investment Advice'],
            default => ['bg-gray-100 text-gray-800', 'Blog'],
        };
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}