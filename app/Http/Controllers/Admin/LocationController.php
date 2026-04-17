<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::withCount('properties');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('area_name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $locations = $query->orderBy('city')->orderBy('area_name')->paginate(12);
        $cities = Location::select('city')->distinct()->pluck('city');
        
        $stats = [
            'total' => Location::count(),
            'popular' => Location::where('is_popular', true)->count(),
            'with_properties' => Location::has('properties')->count(),
            'cities' => Location::select('city')->distinct()->count(),
        ];

        return view('admin.locations.index', compact('locations', 'cities', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'sub_city' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_popular' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['area_name'] . '-' . $validated['city']);
        $validated['is_popular'] = $request->boolean('is_popular', false);

        Location::create($validated);

        return response()->json(['success' => true, 'message' => 'Location created!']);
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'sub_city' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_popular' => 'boolean',
        ]);

        if ($location->area_name !== $validated['area_name'] || $location->city !== $validated['city']) {
            $validated['slug'] = Str::slug($validated['area_name'] . '-' . $validated['city']);
        }

        $validated['is_popular'] = $request->boolean('is_popular', false);

        $location->update($validated);

        return response()->json(['success' => true, 'message' => 'Location updated!']);
    }

    public function destroy(Location $location)
    {
        if ($location->properties()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete location with properties!'], 422);
        }
        $location->delete();
        return response()->json(['success' => true, 'message' => 'Location deleted!']);
    }

    public function togglePopular(Location $location)
    {
        $location->update(['is_popular' => !$location->is_popular]);
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $locations = Location::withCount('properties')->get();
        $filename = 'locations-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];

        $callback = function() use ($locations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Area', 'City', 'Sub City', 'Properties', 'Popular']);
            foreach ($locations as $loc) {
                fputcsv($file, [$loc->id, $loc->area_name, $loc->city, $loc->sub_city, $loc->properties_count, $loc->is_popular ? 'Yes' : 'No']);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}