@extends('layouts.admin')

@section('title', 'Locations Management')

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
<style>
    .location-card {
        transition: all 0.3s ease;
    }
    .location-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15);
    }
    .map-preview {
        height: 140px;
        border-radius: 12px;
        overflow: hidden;
        background: #f3f4f6;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Locations Management</h1>
            <p class="text-gray-600 mt-1">Manage property locations and areas</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.locations.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i class="ri-download-line"></i> Export
            </a>
            <a href="{{ route('admin.locations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-add-line"></i> Add Location
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Locations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-map-pin-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Popular Areas</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['popular'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-line text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">With Properties</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['with_properties'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cities</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['cities'] }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-2-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.locations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search locations..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="city" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="popular" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Locations</option>
                    <option value="yes" {{ request('popular') == 'yes' ? 'selected' : '' }}>Popular Only</option>
                    <option value="no" {{ request('popular') == 'no' ? 'selected' : '' }}>Non-Popular Only</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-filter-line mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-gray-50 rounded-lg p-3 mb-4 hidden">
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">Bulk Actions:</span>
            <button onclick="bulkDelete()" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm">
                <i class="ri-delete-bin-line mr-1"></i> Delete Selected
            </button>
            <span id="selected-count" class="text-sm text-gray-500 ml-auto">0 selected</span>
        </div>
    </div>

    <!-- Locations Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($locations as $location)
        <div class="location-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Map Preview -->
            @if($location->latitude && $location->longitude)
            <div class="map-preview" id="map-{{ $location->id }}"></div>
            @elseif($location->image)
            <img src="{{ $location->image_url }}" alt="{{ $location->area_name }}" class="w-full h-36 object-cover">
            @else
            <div class="h-36 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-center">
                <i class="ri-map-pin-line text-4xl text-blue-300"></i>
            </div>
            @endif
            
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">{{ $location->area_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $location->sub_city ? $location->sub_city . ', ' : '' }}{{ $location->city }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <input type="checkbox" name="location_ids[]" value="{{ $location->id }}" class="location-checkbox rounded border-gray-300">
                        @if($location->is_popular)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full flex items-center gap-1">
                                <i class="ri-star-fill text-xs"></i> Popular
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center gap-4 mb-4 text-sm">
                    <div class="flex items-center gap-1 text-gray-600">
                        <i class="ri-building-line"></i>
                        <span>{{ $location->properties_count }} Properties</span>
                    </div>
                    <div class="flex items-center gap-1 text-gray-600">
                        <i class="ri-checkbox-circle-line text-green-600"></i>
                        <span>{{ $location->active_properties_count }} Active</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <a href="{{ route('properties.location', $location->slug) }}" target="_blank" 
                       class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        <i class="ri-external-link-line"></i> View Properties
                    </a>
                    <div class="flex items-center gap-2">
                        <button onclick="togglePopular({{ $location->id }})" 
                                class="p-2 {{ $location->is_popular ? 'text-yellow-500' : 'text-gray-400' }} hover:text-yellow-600 transition"
                                title="{{ $location->is_popular ? 'Remove from popular' : 'Mark as popular' }}">
                            <i class="ri-star-{{ $location->is_popular ? 'fill' : 'line' }}"></i>
                        </button>
                        <a href="{{ route('admin.locations.show', $location) }}" 
                           class="p-2 text-gray-400 hover:text-blue-600 transition"
                           title="View Details">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="{{ route('admin.locations.edit', $location) }}" 
                           class="p-2 text-gray-400 hover:text-green-600 transition"
                           title="Edit">
                            <i class="ri-edit-line"></i>
                        </a>
                        <button onclick="deleteLocation({{ $location->id }})" 
                                class="p-2 text-gray-400 hover:text-red-600 transition"
                                title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <i class="ri-map-pin-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No locations found</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first location.</p>
                <a href="{{ route('admin.locations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-1"></i> Add Location
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($locations->hasPages())
    <div class="mt-6">
        {{ $locations->links() }}
    </div>
    @endif
</div>

<script>
    // Bulk actions
    const checkboxes = document.querySelectorAll('.location-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const checked = document.querySelectorAll('.location-checkbox:checked');
        const count = checked.length;
        selectedCount.textContent = count + ' selected';
        bulkActions.classList.toggle('hidden', count === 0);
    }
    
    function bulkDelete() {
        const ids = Array.from(document.querySelectorAll('.location-checkbox:checked')).map(cb => cb.value);
        
        if (ids.length === 0) return;
        if (!confirm('Are you sure you want to delete ' + ids.length + ' locations?')) return;
        
        fetch('{{ route("admin.locations.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids })
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
    
    function togglePopular(id) {
        fetch(`/admin/locations/${id}/toggle-popular`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    
    function deleteLocation(id) {
        if (!confirm('Are you sure you want to delete this location?')) return;
        
        fetch(`/admin/locations/${id}`, {
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

@if(config('services.google.maps_api_key'))
<script>
    function initMap() {
        @foreach($locations as $location)
            @if($location->latitude && $location->longitude)
            (function() {
                const mapElement = document.getElementById('map-{{ $location->id }}');
                if (mapElement) {
                    const map = new google.maps.Map(mapElement, {
                        center: { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} },
                        zoom: 13,
                        disableDefaultUI: true,
                        styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":-100}]}]
                    });
                    
                    new google.maps.Marker({
                        position: { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} },
                        map: map
                    });
                }
            })();
            @endif
        @endforeach
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
@endif
@endsection