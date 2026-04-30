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
        'gallery_images', 'video_url', 'category_id', 'author_id', 
        'tags', 'post_type', 'reading_time', 'is_featured', 
        'is_published', 'published_at', 'views', 'meta_title', 
        'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'tags' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'post_type' => 'blog',
        'reading_time' => 5,
        'is_featured' => false,
        'is_published' => false,
        'views' => 0,
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

    public function getGalleryImageUrlsAttribute()
    {
        if (!$this->gallery_images) {
            return [];
        }

        return collect($this->gallery_images)->map(function ($image) {
            if (\Storage::disk('public')->exists($image)) {
                return route('file.show', ['path' => $image]);
            }
            return null;
        })->filter()->values()->toArray();
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

    public function getPostTypeLabelAttribute()
    {
        return match($this->post_type) {
            'blog' => 'Blog',
            'tip' => 'Real Estate Tip',
            'market_update' => 'Market Update',
            'investment' => 'Investment Advice',
            default => 'Blog',
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

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->orderByDesc('views')->limit($limit);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('excerpt', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%")
              ->orWhere('tags', 'like', "%{$term}%");
        });
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getReadingTimeInMinutesAttribute()
    {
        return $this->reading_time . ' min read';
    }

    public function isScheduled()
    {
        return $this->is_published && $this->published_at && $this->published_at->isFuture();
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
        ]);
    }
}