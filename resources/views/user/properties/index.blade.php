@extends('layouts.app')

@section('title', 'My Properties')

@push('styles')
<style>
    .property-card {
        transition: all 0.2s ease;
    }
    .property-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Properties</h1>
                <p class="text-gray-600 mt-1">Manage your property listings</p>
            </div>
            <a href="{{ route('admin.properties.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-add-line"></i> Add Property
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Properties</p>
                <p class="text-2xl font-bold text-gray-900">{{ $properties->total() }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Active Listings</p>
                <p class="text-2xl font-bold text-green-600">{{ $properties->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Views</p>
                <p class="text-2xl font-bold text-blue-600">{{ $properties->sum('views') }}</p>
            </div>
        </div>

        <!-- Properties List -->
        @if($properties->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($properties as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($property->primary_image)
                                        <img src="{{ asset('storage/' . $property->primary_image) }}" 
                                             alt="{{ $property->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="ri-home-4-line text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank" 
                                           class="font-medium text-gray-900 hover:text-blue-600">
                                            {{ Str::limit($property->title, 30) }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ $property->location->area_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">ETB {{ number_format($property->price) }}</span>
                                <span class="text-sm text-gray-500">{{ $property->price_type === 'rent' ? '/mo' : '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-badge {{ $property->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $property->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $property->views }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $property->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('properties.show', $property->slug) }}" target="_blank" 
                                       class="p-2 text-gray-500 hover:text-blue-600" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.properties.edit', $property) }}" 
                                       class="p-2 text-gray-500 hover:text-green-600" title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $properties->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <i class="ri-building-line text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No properties yet</h3>
                <p class="text-gray-500 mb-4">Start by adding your first property listing.</p>
                <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-2"></i> Add Property
                </a>
            </div>
        @endif
    </div>
</div>
@endsection