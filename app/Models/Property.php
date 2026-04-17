<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'location_id',
        'user_id',
        'price',
        'price_type',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area_sqm',
        'year_built',
        'parking',
        'furnished',
        'security',
        'elevator',
        'garden',
        'pool',
        'air_conditioning',
        'status',
        'is_featured',
        'is_active',
        'address',
        'video_url',
        'virtual_tour_url',
        'views',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area_sqm' => 'decimal:2',
        'year_built' => 'integer',
        'parking' => 'boolean',
        'furnished' => 'boolean',
        'security' => 'boolean',
        'elevator' => 'boolean',
        'garden' => 'boolean',
        'pool' => 'boolean',
        'air_conditioning' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'primary_image_url',
        'formatted_price',
        'features_list',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the category that owns the property.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the location that owns the property.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the user (agent) that owns the property.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the images for the property.
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image path.
     */
    public function getPrimaryImageAttribute()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary) {
            return $primary->image_path;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_path;
        }
        
        return null;
    }

    /**
     * Get the primary image URL.
     */
    public function getPrimaryImageUrlAttribute()
    {
        $path = $this->primary_image;
        
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        
        return asset('images/default-property.jpg');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price_type === 'rent') {
            return 'ETB ' . number_format($this->price) . ' / month';
        }
        return 'ETB ' . number_format($this->price);
    }

    /**
     * Get short formatted price (e.g., 8.5M ETB).
     */
    public function getShortPriceAttribute()
    {
        if ($this->price >= 1000000) {
            return 'ETB ' . round($this->price / 1000000, 1) . 'M';
        }
        if ($this->price >= 1000) {
            return 'ETB ' . round($this->price / 1000, 1) . 'K';
        }
        return 'ETB ' . number_format($this->price);
    }

    /**
     * Get the list of features.
     */
    public function getFeaturesAttribute()
    {
        $features = [];
        $featureFields = [
            'parking' => 'Parking',
            'furnished' => 'Furnished',
            'security' => '24/7 Security',
            'elevator' => 'Elevator',
            'garden' => 'Garden',
            'pool' => 'Swimming Pool',
            'air_conditioning' => 'Air Conditioning',
        ];
        
        foreach ($featureFields as $field => $label) {
            if ($this->$field) {
                $features[] = $label;
            }
        }
        
        return $features;
    }

    /**
     * Get the features as an array with icons.
     */
    public function getFeaturesListAttribute()
    {
        $features = [];
        $featureFields = [
            'parking' => ['label' => 'Parking', 'icon' => 'ri-parking-box-line'],
            'furnished' => ['label' => 'Furnished', 'icon' => 'ri-sofa-line'],
            'security' => ['label' => '24/7 Security', 'icon' => 'ri-shield-check-line'],
            'elevator' => ['label' => 'Elevator', 'icon' => 'ri-arrow-up-down-line'],
            'garden' => ['label' => 'Garden', 'icon' => 'ri-plant-line'],
            'pool' => ['label' => 'Swimming Pool', 'icon' => 'ri-water-flash-line'],
            'air_conditioning' => ['label' => 'Air Conditioning', 'icon' => 'ri-windy-line'],
        ];
        
        foreach ($featureFields as $field => $data) {
            if ($this->$field) {
                $features[$field] = $data;
            }
        }
        
        return $features;
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute()
    {
        if ($this->address) {
            return $this->address;
        }
        
        if ($this->location) {
            return $this->location->full_address;
        }
        
        return null;
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'available' => ['bg-green-100 text-green-800', 'Available'],
            'sold' => ['bg-red-100 text-red-800', 'Sold'],
            'rented' => ['bg-blue-100 text-blue-800', 'Rented'],
            'pending' => ['bg-yellow-100 text-yellow-800', 'Pending'],
            default => ['bg-gray-100 text-gray-800', 'Unknown'],
        };
    }

    /**
     * Check if property has any images.
     */
    public function hasImages()
    {
        return $this->images()->count() > 0;
    }

    /**
     * Get similar properties.
     */
    public function getSimilarProperties($limit = 4)
    {
        return self::with(['category', 'location', 'images'])
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Check if property is available.
     */
    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_active;
    }

    /**
     * Check if property is sold.
     */
    public function isSold()
    {
        return $this->status === 'sold';
    }

    /**
     * Check if property is rented.
     */
    public function isRented()
    {
        return $this->status === 'rented';
    }

    /**
     * Scope a query to only include available properties.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    /**
     * Scope a query to only include active properties.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured properties.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include properties for sale.
     */
    public function scopeForSale($query)
    {
        return $query->where('price_type', 'sale');
    }

    /**
     * Scope a query to only include properties for rent.
     */
    public function scopeForRent($query)
    {
        return $query->where('price_type', 'rent');
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope a query to filter by bedrooms.
     */
    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->where('bedrooms', '>=', $bedrooms);
    }

    /**
     * Scope a query to filter by bathrooms.
     */
    public function scopeMinBathrooms($query, $bathrooms)
    {
        return $query->where('bathrooms', '>=', $bathrooms);
    }

    /**
     * Scope a query to filter by area range.
     */
    public function scopeAreaRange($query, $min, $max)
    {
        if ($min) {
            $query->where('area_sqm', '>=', $min);
        }
        if ($max) {
            $query->where('area_sqm', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope a query to search properties.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%")
              ->orWhereHas('location', function($loc) use ($term) {
                  $loc->where('area_name', 'like', "%{$term}%")
                      ->orWhere('city', 'like', "%{$term}%");
              })
              ->orWhereHas('category', function($cat) use ($term) {
                  $cat->where('name', 'like', "%{$term}%");
              });
        });
    }

    /**
     * Scope a query to sort properties.
     */
    public function scopeSortBy($query, $sort = 'latest')
    {
        return match($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'oldest' => $query->oldest(),
            'most_viewed' => $query->orderBy('views', 'desc'),
            'featured' => $query->where('is_featured', true)->latest(),
            default => $query->latest(),
        };
    }

    /**
     * Increment the view count.
     */
    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($property) {
            if (empty($property->views)) {
                $property->views = 0;
            }
            if (empty($property->status)) {
                $property->status = 'available';
            }
        });

        static::deleting(function ($property) {
            // Delete associated images from storage
            foreach ($property->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        });
    }
}