<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['category', 'location', 'user', 'images'])
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price type filter
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        $properties = $query->paginate(15)->withQueryString();
        
        $categories = Category::where('is_active', true)->get();
        $stats = [
            'total' => Property::count(),
            'active' => Property::where('is_active', true)->count(),
            'featured' => Property::where('is_featured', true)->count(),
            'for_sale' => Property::where('price_type', 'sale')->count(),
            'for_rent' => Property::where('price_type', 'rent')->count(),
        ];

        return view('admin.properties.index', compact('properties', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();
        $agents = User::where('role', 'agent')->orWhere('is_admin', true)->get();
        
        return view('admin.properties.create', compact('categories', 'locations', 'agents'));
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:sale,rent',
            'property_type' => 'required|in:apartment,villa,commercial,land,office',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqm' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:available,sold,rented,pending',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'parking' => 'boolean',
            'furnished' => 'boolean',
            'security' => 'boolean',
            'elevator' => 'boolean',
            'garden' => 'boolean',
            'pool' => 'boolean',
            'air_conditioning' => 'boolean',
            'video_url' => 'nullable|url',
            'virtual_tour_url' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Check for unique slug
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Property::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $property = Property::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property created successfully!');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load(['category', 'location', 'user', 'images']);
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();
        $agents = User::where('role', 'agent')->orWhere('is_admin', true)->get();
        $property->load('images');
        
        return view('admin.properties.edit', compact('property', 'categories', 'locations', 'agents'));
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:sale,rent',
            'property_type' => 'required|in:apartment,villa,commercial,land,office',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqm' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:available,sold,rented,pending',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'parking' => 'boolean',
            'furnished' => 'boolean',
            'security' => 'boolean',
            'elevator' => 'boolean',
            'garden' => 'boolean',
            'pool' => 'boolean',
            'air_conditioning' => 'boolean',
            'video_url' => 'nullable|url',
            'virtual_tour_url' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Update slug if title changed
        if ($property->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Property::where('slug', $validated['slug'])->where('id', '!=', $property->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $property->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $sortOrder = $property->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'sort_order' => $sortOrder + $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        // Delete images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Property $property)
    {
        $property->update(['is_featured' => !$property->is_featured]);
        
        return response()->json([
            'success' => true,
            'is_featured' => $property->is_featured,
            'message' => $property->is_featured ? 'Property featured!' : 'Property unfeatured!'
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Property $property)
    {
        $property->update(['is_active' => !$property->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $property->is_active,
            'message' => $property->is_active ? 'Property activated!' : 'Property deactivated!'
        ]);
    }

    /**
     * Upload additional images.
     */
    public function uploadImages(Request $request, Property $property)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $sortOrder = $property->images()->max('sort_order') + 1;
        
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('properties/' . $property->id, 'public');
            
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => $path,
                'sort_order' => $sortOrder + $index,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully!'
        ]);
    }

    /**
     * Delete an image.
     */
    public function deleteImage(Property $property, PropertyImage $image)
    {
        if ($image->property_id !== $property->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        // Set new primary if needed
        if ($image->is_primary) {
            $firstImage = $property->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Image deleted!']);
    }

    /**
     * Reorder images.
     */
    public function reorderImages(Request $request, Property $property)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:property_images,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            PropertyImage::where('id', $order['id'])
                ->where('property_id', $property->id)
                ->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Images reordered!']);
    }

    /**
     * Bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature',
            'ids' => 'required|array',
            'ids.*' => 'exists:properties,id',
        ]);

        $properties = Property::whereIn('id', $request->ids);
        $message = '';

        switch ($request->action) {
            case 'delete':
                foreach ($properties->get() as $property) {
                    foreach ($property->images as $image) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
                $properties->delete();
                $message = count($request->ids) . ' properties deleted!';
                break;
            case 'activate':
                $properties->update(['is_active' => true]);
                $message = count($request->ids) . ' properties activated!';
                break;
            case 'deactivate':
                $properties->update(['is_active' => false]);
                $message = count($request->ids) . ' properties deactivated!';
                break;
            case 'feature':
                $properties->update(['is_featured' => true]);
                $message = count($request->ids) . ' properties featured!';
                break;
            case 'unfeature':
                $properties->update(['is_featured' => false]);
                $message = count($request->ids) . ' properties unfeatured!';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

  /**
 * Import properties from CSV.
 */
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt|max:10240',
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getPathname(), 'r');
    
    // Skip header row
    fgetcsv($handle);
    
    $imported = 0;
    $errors = 0;
    
    while (($row = fgetcsv($handle)) !== false) {
        try {
            // Find or create category
            $category = Category::firstOrCreate(
                ['name' => $row[3] ?? 'Uncategorized'],
                ['is_active' => true]
            );
            
            // Find or create location
            $location = Location::firstOrCreate(
                ['area_name' => $row[4] ?? 'Unknown'],
                ['city' => 'Addis Ababa', 'is_popular' => false]
            );
            
            Property::create([
                'title' => $row[1] ?? 'Untitled',
                'description' => $row[2] ?? '',
                'category_id' => $category->id,
                'location_id' => $location->id,
                'user_id' => auth()->id(),
                'price' => floatval($row[5] ?? 0),
                'price_type' => $row[6] ?? 'sale',
                'property_type' => $row[7] ?? 'apartment',
                'bedrooms' => intval($row[8] ?? 0),
                'bathrooms' => intval($row[9] ?? 0),
                'area_sqm' => floatval($row[10] ?? 0),
                'year_built' => intval($row[11] ?? null),
                'status' => $row[12] ?? 'available',
                'is_featured' => ($row[13] ?? 'No') === 'Yes',
                'is_active' => ($row[14] ?? 'Yes') === 'Yes',
                'views' => 0,
            ]);
            
            $imported++;
        } catch (\Exception $e) {
            $errors++;
        }
    }
    
    fclose($handle);

    return redirect()
        ->route('admin.properties.index')
        ->with('success', "{$imported} properties imported successfully! {$errors} errors.");
}

    /**
     * Export properties to CSV.
     */
    /**
 * Export properties to CSV.
 */
public function export()
{
    $properties = Property::with(['category', 'location', 'user'])->get();
    
    $filename = 'properties-' . date('Y-m-d') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($properties) {
        $file = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($file, [
            'ID', 
            'Title', 
            'Description', 
            'Category', 
            'Location', 
            'Price', 
            'Price Type', 
            'Property Type',
            'Bedrooms', 
            'Bathrooms', 
            'Area (m²)', 
            'Year Built',
            'Status', 
            'Featured', 
            'Active',
            'Views',
            'Agent',
            'Created At'
        ]);
        
        // Add data rows
        foreach ($properties as $property) {
            fputcsv($file, [
                $property->id,
                $property->title,
                strip_tags($property->description),
                $property->category->name ?? 'N/A',
                $property->location->area_name ?? 'N/A',
                $property->price,
                $property->price_type,
                $property->property_type,
                $property->bedrooms ?? '-',
                $property->bathrooms ?? '-',
                $property->area_sqm ?? '-',
                $property->year_built ?? '-',
                $property->status,
                $property->is_featured ? 'Yes' : 'No',
                $property->is_active ? 'Yes' : 'No',
                $property->views,
                $property->user->name ?? 'N/A',
                $property->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}