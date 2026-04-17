@extends('layouts.admin')

@section('title', 'Edit Location')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Location</h1>
            <p class="text-gray-600 mt-1">Update location information</p>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.locations.update', $location) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Area Name *</label>
                    <input type="text" name="area_name" value="{{ old('area_name', $location->area_name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('area_name') border-red-300 @enderror">
                    @error('area_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <input type="text" name="city" value="{{ old('city', $location->city) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('city') border-red-300 @enderror">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub City</label>
                    <input type="text" name="sub_city" value="{{ old('sub_city', $location->sub_city) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Image</label>
                    @if($location->image)
                        <img src="{{ $location->image_url }}" alt="{{ $location->area_name }}" class="w-full h-24 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" name="latitude" value="{{ old('latitude', $location->latitude) }}" step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" name="longitude" value="{{ old('longitude', $location->longitude) }}" step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $location->is_popular) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">Mark as popular location</span>
                </label>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Update Location
                </button>
                <a href="{{ route('admin.locations.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection