@extends('layouts.admin')

@section('title', 'Add New Location')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Location</h1>
            <p class="text-gray-600 mt-1">Create a new property location</p>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Area Name *</label>
                    <input type="text" name="area_name" value="{{ old('area_name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('area_name') border-red-300 @enderror"
                           placeholder="e.g., Bole, Piassa, CMC">
                    @error('area_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <input type="text" name="city" value="{{ old('city', 'Addis Ababa') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('city') border-red-300 @enderror"
                           placeholder="e.g., Addis Ababa">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub City</label>
                    <input type="text" name="sub_city" value="{{ old('sub_city') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           placeholder="e.g., Bole Sub City">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Image</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Optional. Recommended: 1200x600px</p>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" name="latitude" value="{{ old('latitude') }}" step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           placeholder="e.g., 9.0320">
                    <p class="text-xs text-gray-500 mt-1">For map display</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" name="longitude" value="{{ old('longitude') }}" step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           placeholder="e.g., 38.7520">
                    <p class="text-xs text-gray-500 mt-1">For map display</p>
                </div>
            </div>
            
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">Mark as popular location</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Popular locations appear on the homepage</p>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Create Location
                </button>
                <a href="{{ route('admin.locations.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection