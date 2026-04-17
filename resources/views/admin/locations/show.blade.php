@extends('layouts.admin')

@section('title', $location->area_name . ' - Location Details')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .property-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.locations.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $location->area_name }}</h1>
                <p class="text-gray-600">{{ $location->full_address }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.locations.edit', $location) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="ri-edit-line mr-1"></i> Edit
            </a>
            <button onclick="deleteLocation({{ $location->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="ri-delete-bin-line mr-1"></i> Delete
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <p class="text-sm text-gray-500">Total Properties</p>
            <p class="text-3xl font-bold text-gray-900">{{ $location->properties_count }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500">Active Properties</p>
            <p class="text-3xl font-bold text-green-600">{{ $location->active_properties_count }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500">Popular</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $location->is_popular ? 'Yes' : 'No' }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-gray-500">Created</p>
            <p class="text-lg font-medium text-gray-900">{{ $location->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Location Image & Map -->
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        @if($location->image)
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <h3 class="font-semibold mb-3">Location Image</h3>
            <img src="{{ $location->image_url }}" alt="{{ $location->area_name }}" class="w-full h-48 object-cover rounded-lg">
        </div>
        @endif
        
        @if($location->latitude && $location->longitude)
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <h3 class="font-semibold mb-3">Map Location</h3>
            <div class="h-48 rounded-lg overflow-hidden">
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    src="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}&z=14&output=embed">
                </iframe>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Properties -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b">
            <h3 class="font-semibold text-gray-900">Recent Properties in {{ $location->area_name }}</h3>
        </div>
        <div class="divide-y">
            @forelse($recentProperties as $property)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-center gap-4">
                    @if($property->primary_image)
                    <img src="{{ route('file.show', ['path' => $property->primary_image]) }}" alt="{{ $property->title }}" class="property-thumb">
                    @else
                    <div class="property-thumb bg-gray-200 flex items-center justify-center">
                        <i class="ri-home-4-line text-gray-400"></i>
                    </div>
                    @endif
                    <div class="flex-1">
                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="font-medium hover:text-blue-600">
                            {{ $property->title }}
                        </a>
                        <p class="text-sm text-gray-500">ETB {{ number_format($property->price) }}</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $property->created_at->format('M d, Y') }}</span>
                    <a href="{{ route('admin.properties.edit', $property) }}" class="p-2 text-gray-400 hover:text-blue-600">
                        <i class="ri-edit-line"></i>
                    </a>
                </div>
            </div>
            @empty
            <p class="p-8 text-center text-gray-500">No properties in this location yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
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
                window.location.href = '{{ route("admin.locations.index") }}';
            } else {
                alert(data.message);
            }
        });
    }
</script>
@endsection