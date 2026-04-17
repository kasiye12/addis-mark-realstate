<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'bio',
        'image',
        'email',
        'phone',
        'social_links',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'image_url',
        'initials',
    ];

    /**
     * Get the member's image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=2563eb&color=fff';
    }

    /**
     * Get member initials.
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get social links as array with icons.
     */
    public function getSocialLinksWithIconsAttribute()
    {
        $links = [];
        $icons = [
            'facebook' => 'ri-facebook-fill',
            'twitter' => 'ri-twitter-x-fill',
            'linkedin' => 'ri-linkedin-fill',
            'instagram' => 'ri-instagram-fill',
            'whatsapp' => 'ri-whatsapp-fill',
            'telegram' => 'ri-telegram-fill',
        ];

        if ($this->social_links) {
            foreach ($this->social_links as $platform => $url) {
                if ($url) {
                    $links[$platform] = [
                        'url' => $url,
                        'icon' => $icons[$platform] ?? 'ri-link',
                    ];
                }
            }
        }

        return $links;
    }

    /**
     * Scope a query to only include active members.
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
        return $query->orderBy('sort_order')->orderBy('name');
    }
}