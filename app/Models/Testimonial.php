<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_position',
        'client_company',
        'client_image',
        'content',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'image_url',
        'initials',
        'rating_stars',
    ];

    /**
     * Get the client's image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->client_image && Storage::disk('public')->exists($this->client_image)) {
            return asset('storage/' . $this->client_image);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->client_name) . '&size=100&background=2563eb&color=fff';
    }

    /**
     * Get client initials.
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->client_name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->client_name, 0, 2));
    }

    /**
     * Get rating as stars array.
     */
    public function getRatingStarsAttribute()
    {
        return array_fill(0, 5, [
            'filled' => false,
        ]);
    }

    /**
     * Get rating percentage (for display).
     */
    public function getRatingPercentAttribute()
    {
        return ($this->rating / 5) * 100;
    }

    /**
     * Scope a query to only include active testimonials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Get short content preview.
     */
    public function getShortContentAttribute($length = 100)
    {
        return \Str::limit($this->content, $length);
    }
}