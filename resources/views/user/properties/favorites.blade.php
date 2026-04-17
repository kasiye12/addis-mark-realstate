@extends('layouts.app')

@section('title', 'Favorite Properties')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Favorite Properties</h1>
            <p class="text-gray-600 mt-1">Properties you've saved for later</p>
        </div>

        @if(isset($properties) && $properties->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="relative h-48">
                        @if($property->primary_image)
                            <img src="{{ asset('storage/' . $property->primary_image) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="ri-home-4-line text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded">
                            {{ ucfirst($property->price_type) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $property->title }}</h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $property->location->area_name ?? 'N/A' }}</p>
                        <p class="text-lg font-bold text-blue-600 mb-3">ETB {{ number_format($property->price) }}</p>
                        <div class="flex gap-2">
                            <a href="{{ route('properties.show', $property->slug) }}" 
                               class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                View Details
                            </a>
                            <button onclick="removeFavorite({{ $property->id }})" 
                                    class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="ri-heart-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <i class="ri-heart-line text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No favorites yet</h3>
                <p class="text-gray-500 mb-4">Browse properties and save your favorites.</p>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-search-line mr-2"></i> Browse Properties
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function removeFavorite(propertyId) {
        fetch(`/properties/${propertyId}/favorite`, {
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
</script>
@endsection