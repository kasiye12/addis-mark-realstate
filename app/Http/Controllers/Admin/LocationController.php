<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of locations.
     */
    public function index(Request $request)
    {
        $query = Location::withCount(['properties', 'properties as active_properties_count' => function($q) {
            $q->where('is_active', true);
        }]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('area_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('sub_city', 'like', "%{$search}%");
            });
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Popular filter
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'yes');
        }

        $locations = $query->orderBy('city')
            ->orderBy('area_name')
            ->paginate(15)
            ->withQueryString();

        // Get unique cities for filter
        $cities = Location::select('city')->distinct()->orderBy('city')->pluck('city');

        // Statistics
        $stats = [
            'total' => Location::count(),
            'popular' => Location::where('is_popular', true)->count(),
            'with_properties' => Location::has('properties')->count(),
            'cities' => Location::select('city')->distinct()->count(),
        ];

        return view('admin.locations.index', compact('locations', 'cities', 'stats'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created location.
     */
    /**
 * Store a newly created location.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'city' => 'required|string|max:100',
        'sub_city' => 'nullable|string|max:100',
        'area_name' => 'required|string|max:100',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'is_popular' => 'boolean',
    ]);

    // Generate slug
    $validated['slug'] = Str::slug($validated['area_name'] . '-' . $validated['city']);
    
    // Check for unique slug
    $count = 1;
    $originalSlug = $validated['slug'];
    while (Location::where('slug', $validated['slug'])->exists()) {
        $validated['slug'] = $originalSlug . '-' . $count;
        $count++;
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        // Ensure directory exists
        if (!Storage::disk('public')->exists('locations')) {
            Storage::disk('public')->makeDirectory('locations');
        }
        
        $file = $request->file('image');
        $filename = 'location-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $validated['image'] = $file->storeAs('locations', $filename, 'public');
        
        \Log::info('Location image uploaded: ' . $validated['image']);
    }

    $validated['is_popular'] = $request->boolean('is_popular', false);

    $location = Location::create($validated);

    return redirect()
        ->route('admin.locations.index')
        ->with('success', 'Location created successfully!');
}

/**
 * Update the specified location.
 */
public function update(Request $request, Location $location)
{
    $validated = $request->validate([
        'city' => 'required|string|max:100',
        'sub_city' => 'nullable|string|max:100',
        'area_name' => 'required|string|max:100',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'is_popular' => 'boolean',
    ]);

    // Update slug if name or city changed
    if ($location->area_name !== $validated['area_name'] || $location->city !== $validated['city']) {
        $validated['slug'] = Str::slug($validated['area_name'] . '-' . $validated['city']);
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Location::where('slug', $validated['slug'])->where('id', '!=', $location->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image
        if ($location->image && Storage::disk('public')->exists($location->image)) {
            Storage::disk('public')->delete($location->image);
            \Log::info('Old location image deleted: ' . $location->image);
        }
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists('locations')) {
            Storage::disk('public')->makeDirectory('locations');
        }
        
        $file = $request->file('image');
        $filename = 'location-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $validated['image'] = $file->storeAs('locations', $filename, 'public');
        
        \Log::info('New location image uploaded: ' . $validated['image']);
    }

    $validated['is_popular'] = $request->boolean('is_popular', false);

    $location->update($validated);

    // Clear cache for this location
    cache()->forget('location_' . $location->id);

    return redirect()
        ->route('admin.locations.index')
        ->with('success', 'Location updated successfully!');
}

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        $location->loadCount(['properties', 'properties as active_properties_count' => function($q) {
            $q->where('is_active', true);
        }]);
        
        $recentProperties = $location->properties()
            ->with(['category', 'images'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.locations.show', compact('location', 'recentProperties'));
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    

    /**
     * Remove the specified location.
     */
    public function destroy(Location $location)
    {
        // Check if location has properties
        if ($location->properties()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete location with associated properties! Please reassign or delete properties first.'
            ], 422);
        }

        // Delete image
        if ($location->image) {
            Storage::disk('public')->delete($location->image);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully!'
        ]);
    }

    /**
     * Toggle popular status.
     */
    public function togglePopular(Location $location)
    {
        $location->update(['is_popular' => !$location->is_popular]);

        return response()->json([
            'success' => true,
            'is_popular' => $location->is_popular,
            'message' => $location->is_popular ? 'Location marked as popular!' : 'Location removed from popular!'
        ]);
    }

    /**
     * Bulk delete locations.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:locations,id',
        ]);

        $locationsWithProperties = Location::whereIn('id', $request->ids)
            ->has('properties')
            ->count();

        if ($locationsWithProperties > 0) {
            return response()->json([
                'success' => false,
                'message' => "{$locationsWithProperties} location(s) have associated properties and cannot be deleted."
            ], 422);
        }

        $locations = Location::whereIn('id', $request->ids)->get();
        foreach ($locations as $location) {
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
        }

        Location::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' locations deleted successfully!'
        ]);
    }

    /**
     * Export locations to CSV.
     */
    public function export()
    {
        $locations = Location::withCount('properties')->get();
        
        $filename = 'locations-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($locations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Area Name', 'Sub City', 'City', 'Properties Count', 'Popular', 'Created At']);
            
            foreach ($locations as $location) {
                fputcsv($file, [
                    $location->id,
                    $location->area_name,
                    $location->sub_city ?? '-',
                    $location->city,
                    $location->properties_count,
                    $location->is_popular ? 'Yes' : 'No',
                    $location->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search locations for autocomplete.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $locations = Location::where('area_name', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->orWhere('sub_city', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'area_name', 'city', 'sub_city', 'slug']);

        return response()->json($locations);
    }
}