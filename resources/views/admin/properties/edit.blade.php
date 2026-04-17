@extends('layouts.admin')

@section('title', 'Edit Property')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-color: #e5e7eb;
        border-radius: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 16px;
    }
    .image-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Property</h1>
            <p class="text-gray-600 mt-1">Update property information</p>
        </div>
        <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Title *</label>
                            <input type="text" name="title" value="{{ old('title', $property->title) }}" required
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                            <textarea name="description" rows="6" required
                                      class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                <select name="category_id" required class="w-full select2 @error('category_id') border-red-300 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location *</label>
                                <select name="location_id" required class="w-full select2 @error('location_id') border-red-300 @enderror">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $property->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->area_name }}, {{ $location->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" value="{{ old('address', $property->address) }}"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Property Details</h2>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <input type="number" name="price" value="{{ old('price', $property->price) }}" step="0.01" min="0" required
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('price') border-red-300 @enderror">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Type *</label>
                            <select name="price_type" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                                <option value="sale" {{ old('price_type', $property->price_type) == 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ old('price_type', $property->price_type) == 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type *</label>
                            <select name="property_type" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                                <option value="apartment" {{ old('property_type', $property->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="villa" {{ old('property_type', $property->property_type) == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="commercial" {{ old('property_type', $property->property_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="land" {{ old('property_type', $property->property_type) == 'land' ? 'selected' : '' }}>Land</option>
                                <option value="office" {{ old('property_type', $property->property_type) == 'office' ? 'selected' : '' }}>Office</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                            <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                            <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Area (m²)</label>
                            <input type="number" name="area_sqm" value="{{ old('area_sqm', $property->area_sqm) }}" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year Built</label>
                            <input type="number" name="year_built" value="{{ old('year_built', $property->year_built) }}" min="1900" max="{{ date('Y') }}"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Features & Amenities</h2>
                    
                    <div class="grid grid-cols-4 gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="parking" value="1" {{ old('parking', $property->parking) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Parking</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="furnished" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Furnished</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="security" value="1" {{ old('security', $property->security) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">24/7 Security</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="elevator" value="1" {{ old('elevator', $property->elevator) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Elevator</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="garden" value="1" {{ old('garden', $property->garden) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Garden</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="pool" value="1" {{ old('pool', $property->pool) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Swimming Pool</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="air_conditioning" value="1" {{ old('air_conditioning', $property->air_conditioning) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Air Conditioning</span>
                        </label>
                    </div>
                </div>

                <!-- Current Images -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Images</h2>
                    
                    @if($property->images->count() > 0)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($property->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="image-preview w-full">
                                    @if($image->is_primary)
                                        <span class="absolute top-2 left-2 px-2 py-1 bg-blue-600 text-white text-xs rounded-full">Primary</span>
                                    @endif
                                    <button type="button" onclick="deleteImage({{ $property->id }}, {{ $image->id }})" 
                                            class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                                        <i class="ri-delete-bin-line text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No images uploaded yet.</p>
                    @endif
                </div>

                <!-- Add More Images -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Add More Images</h2>
                    
                    <div>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-500 mt-1">You can select multiple images.</p>
                    </div>
                </div>

                <!-- Media URLs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Media URLs</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Video Tour URL</label>
                            <input type="url" name="video_url" value="{{ old('video_url', $property->video_url) }}" placeholder="https://youtube.com/..."
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Virtual Tour URL</label>
                            <input type="url" name="virtual_tour_url" value="{{ old('virtual_tour_url', $property->virtual_tour_url) }}"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Publishing</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                                <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="pending" {{ old('status', $property->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Agent *</label>
                            <select name="user_id" required class="w-full select2">
                                <option value="">Select Agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('user_id', $property->user_id) == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Featured Property</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $property->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="text-sm">Active Listing</span>
                        </label>
                    </div>
                </div>

                <!-- Property Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Property Stats</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Views</span>
                            <span class="font-medium">{{ number_format($property->views) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created</span>
                            <span class="font-medium">{{ $property->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium">{{ $property->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i class="ri-save-line mr-2"></i> Update Property
                    </button>
                    <a href="{{ route('admin.properties.index') }}" class="block text-center mt-3 text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function deleteImage(propertyId, imageId) {
    if (!confirm('Are you sure you want to delete this image?')) return;
    
    fetch(`/admin/properties/${propertyId}/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true
        });
    });
</script>
@endpush