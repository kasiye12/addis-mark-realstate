@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-8">Dashboard</h1>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Properties</p>
                    <p class="text-3xl font-bold">{{ $stats['properties'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Properties</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active_properties'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Categories</p>
                    <p class="text-3xl font-bold">{{ $stats['categories'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-folder-line text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Featured Properties</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['featured_properties'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-line text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Properties -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Recent Properties</h2>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm">
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Category</th>
                        <th class="pb-4">Location</th>
                        <th class="pb-4">Price</th>
                        <th class="pb-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProperties as $property)
                    <tr class="border-t">
                        <td class="py-4">{{ $property->title }}</td>
                        <td class="py-4">{{ $property->category->name ?? 'N/A' }}</td>
                        <td class="py-4">{{ $property->location->area_name ?? 'N/A' }}</td>
                        <td class="py-4">{{ number_format($property->price) }} ETB</td>
                        <td class="py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $property->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $property->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection