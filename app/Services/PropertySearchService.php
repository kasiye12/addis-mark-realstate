<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PropertySearchService
{
    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->query = Property::with(['category', 'location', 'images'])->active();
    }

    public function search(): Builder
    {
        $this->applyCategoryFilter()
             ->applyLocationFilter()
             ->applyPriceTypeFilter()
             ->applyPropertyTypeFilter()
             ->applyPriceRangeFilter()
             ->applyBedroomsFilter()
             ->applyBathroomsFilter()
             ->applyAreaFilter()
             ->applyFeaturesFilter()
             ->applySorting();

        return $this->query;
    }

    protected function applyCategoryFilter(): self
    {
        if ($this->request->filled('category')) {
            $this->query->whereHas('category', function ($q) {
                $q->where('slug', $this->request->category);
            });
        }
        return $this;
    }

    protected function applyLocationFilter(): self
    {
        if ($this->request->filled('location')) {
            $this->query->whereHas('location', function ($q) {
                $q->where('slug', $this->request->location);
            });
        }
        return $this;
    }

    protected function applyPriceTypeFilter(): self
    {
        if ($this->request->filled('price_type')) {
            $this->query->where('price_type', $this->request->price_type);
        }
        return $this;
    }

    protected function applyPropertyTypeFilter(): self
    {
        if ($this->request->filled('property_type')) {
            $this->query->where('property_type', $this->request->property_type);
        }
        return $this;
    }

    protected function applyPriceRangeFilter(): self
    {
        if ($this->request->filled('min_price')) {
            $this->query->where('price', '>=', $this->request->min_price);
        }
        if ($this->request->filled('max_price')) {
            $this->query->where('price', '<=', $this->request->max_price);
        }
        return $this;
    }

    protected function applyBedroomsFilter(): self
    {
        if ($this->request->filled('bedrooms')) {
            $this->query->where('bedrooms', '>=', $this->request->bedrooms);
        }
        return $this;
    }

    protected function applyBathroomsFilter(): self
    {
        if ($this->request->filled('bathrooms')) {
            $this->query->where('bathrooms', '>=', $this->request->bathrooms);
        }
        return $this;
    }

    protected function applyAreaFilter(): self
    {
        if ($this->request->filled('min_area')) {
            $this->query->where('area_sqm', '>=', $this->request->min_area);
        }
        if ($this->request->filled('max_area')) {
            $this->query->where('area_sqm', '<=', $this->request->max_area);
        }
        return $this;
    }

    protected function applyFeaturesFilter(): self
    {
        $features = ['parking', 'furnished', 'security', 'elevator', 'garden', 'pool', 'air_conditioning'];
        
        foreach ($features as $feature) {
            if ($this->request->has($feature) && $this->request->$feature) {
                $this->query->where($feature, true);
            }
        }
        return $this;
    }

    protected function applySorting(): self
    {
        $sort = $this->request->get('sort', 'latest');
        
        switch ($sort) {
            case 'price_low':
                $this->query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $this->query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $this->query->oldest();
                break;
            case 'most_viewed':
                $this->query->orderBy('views', 'desc');
                break;
            default:
                $this->query->latest();
        }
        
        return $this;
    }

    public function getFilterSummary(): array
    {
        $summary = [];
        
        if ($this->request->filled('category')) {
            $summary['Category'] = $this->request->category;
        }
        if ($this->request->filled('location')) {
            $summary['Location'] = $this->request->location;
        }
        if ($this->request->filled('price_range')) {
            $summary['Price Range'] = "ETB {$this->request->min_price} - {$this->request->max_price}";
        }
        
        return $summary;
    }
}