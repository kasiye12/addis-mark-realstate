<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties with filters.
     */
    public function index(Request $request)
    {
        $query = Property::with(['category', 'location', 'images'])
            ->where('is_active', true);

        // Apply category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('slug', $request->location);
            });
        }

        // Apply price type filter (sale/rent)
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        // Apply property type filter
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply bedrooms filter
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Apply bathrooms filter
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        // Apply area filter
        if ($request->filled('min_area')) {
            $query->where('area_sqm', '>=', $request->min_area);
        }
        if ($request->filled('max_area')) {
            $query->where('area_sqm', '<=', $request->max_area);
        }

        // Apply feature filters
        $features = ['parking', 'furnished', 'security', 'elevator', 'garden', 'pool', 'air_conditioning'];
        foreach ($features as $feature) {
            if ($request->has($feature) && $request->$feature) {
                $query->where($feature, true);
            }
        }

        // Apply search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('location', function($loc) use ($search) {
                      $loc->where('area_name', 'like', "%{$search}%")
                          ->orWhere('city', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'most_viewed':
                $query->orderBy('views', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->latest();
                break;
            default:
                $query->latest();
        }

        // Get paginated results
        $properties = $query->paginate($request->get('per_page', 12))->withQueryString();
        
        // Get filter options
        $categories = Category::where('is_active', true)->get();
        $locations = Location::where('is_popular', true)->get();
        $propertyTypes = ['apartment', 'villa', 'commercial', 'land', 'office'];
        
        // Get price range for filter display
        $priceRange = [
            'min' => Property::where('is_active', true)->min('price') ?? 0,
            'max' => Property::where('is_active', true)->max('price') ?? 10000000,
        ];

        // Get area range for filter display
        $areaRange = [
            'min' => Property::where('is_active', true)->whereNotNull('area_sqm')->min('area_sqm') ?? 0,
            'max' => Property::where('is_active', true)->whereNotNull('area_sqm')->max('area_sqm') ?? 500,
        ];

        // Check if it's an AJAX request for infinite scroll or filter updates
        if ($request->ajax()) {
            return view('frontend.properties.partials.property-grid', compact('properties'))->render();
        }

        return view('frontend.properties.index', compact(
            'properties',
            'categories',
            'locations',
            'propertyTypes',
            'priceRange',
            'areaRange'
        ));
    }

    /**
     * Display the specified property.
     */
    public function show($slug)
    {
        $property = Property::with(['category', 'location', 'images', 'user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $property->increment('views');

        // Get similar properties
        $similarProperties = Property::with(['category', 'location', 'images'])
            ->where('category_id', $property->category_id)
            ->where('id', '!=', $property->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // Get nearby properties (same location)
        $nearbyProperties = Property::with(['category', 'images'])
            ->where('location_id', $property->location_id)
            ->where('id', '!=', $property->id)
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        // Get property features as array
        $features = [];
        $featureFields = ['parking', 'furnished', 'security', 'elevator', 'garden', 'pool', 'air_conditioning'];
        foreach ($featureFields as $field) {
            if ($property->$field) {
                $features[] = ucfirst(str_replace('_', ' ', $field));
            }
        }

        return view('frontend.properties.show', compact(
            'property',
            'similarProperties',
            'nearbyProperties',
            'features'
        ));
    }

    /**
     * Display properties by category.
     */
    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $query = Property::with(['location', 'images'])
            ->where('category_id', $category->id)
            ->where('is_active', true);

        // Apply additional filters
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $properties = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.category', compact(
            'properties',
            'categories',
            'category'
        ));
    }

    /**
     * Display properties by location.
     */
    public function location($slug, Request $request)
    {
        $location = Location::where('slug', $slug)->firstOrFail();
        
        $query = Property::with(['category', 'images'])
            ->where('location_id', $location->id)
            ->where('is_active', true);

        // Apply additional filters
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $properties = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.location', compact(
            'properties',
            'categories',
            'location'
        ));
    }

    /**
     * Display featured properties.
     */
    public function featured(Request $request)
    {
        $query = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true);

        $properties = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.featured', compact('properties', 'categories'));
    }

    /**
     * Display properties for sale.
     */
    public function forSale(Request $request)
    {
        $query = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('price_type', 'sale');

        // Apply filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.for-sale', compact('properties', 'categories'));
    }

    /**
     * Display properties for rent.
     */
    public function forRent(Request $request)
    {
        $query = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('price_type', 'rent');

        // Apply filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.for-rent', compact('properties', 'categories'));
    }

    /**
     * Display new listings.
     */
    public function newListings()
    {
        $properties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->paginate(12);
            
        $categories = Category::where('is_active', true)->get();

        return view('frontend.properties.new-listings', compact('properties', 'categories'));
    }

    /**
     * Display properties by type.
     */
    public function type($type, Request $request)
    {
        $validTypes = ['apartment', 'villa', 'commercial', 'land', 'office'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $query = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('property_type', $type);

        $properties = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $currentType = ucfirst($type);

        return view('frontend.properties.type', compact('properties', 'categories', 'currentType', 'type'));
    }

    /**
     * Toggle favorite property (requires auth).
     */
    public function toggleFavorite(Property $property)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to save favorites'], 401);
        }

        $user = auth()->user();
        
        // Assuming you have a favorites relationship
        // if ($user->favorites()->where('property_id', $property->id)->exists()) {
        //     $user->favorites()->detach($property->id);
        //     $status = 'removed';
        // } else {
        //     $user->favorites()->attach($property->id);
        //     $status = 'added';
        // }

        // For now, return success
        return response()->json(['success' => true]);
    }

    /**
     * Share property.
     */
    public function share(Property $property, Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:500',
        ]);

        // Send share email logic here
        
        return redirect()->back()->with('success', 'Property shared successfully!');
    }

    /**
     * Download property brochure.
     */
    public function downloadBrochure(Property $property)
    {
        // Generate PDF brochure logic here
        // For now, redirect back
        return redirect()->back()->with('info', 'Brochure download feature coming soon!');
    }

    /**
     * Get nearby properties (AJAX).
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|numeric|max:50',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 10; // km

        // If you have latitude/longitude in your location table
        $properties = Property::with(['category', 'images'])
            ->where('is_active', true)
            ->whereHas('location', function($query) use ($lat, $lng, $radius) {
                // Haversine formula for distance calculation
                $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
                      ->having('distance', '<=', $radius)
                      ->orderBy('distance');
            })
            ->take(10)
            ->get();

        return response()->json($properties);
    }
}