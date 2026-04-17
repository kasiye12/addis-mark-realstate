@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Here's what's happening with your real estate business today.</p>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.properties.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i class="ri-add-line"></i> Add Property
        </a>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
            <i class="ri-user-add-line"></i> Add User
        </a>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2">
            <i class="ri-folder-add-line"></i> Manage Categories
        </a>
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center gap-2">
            <i class="ri-settings-line"></i> Settings
        </a>
        <form action="{{ route('admin.quick-action') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="action" value="clear_cache">
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                <i class="ri-delete-back-line"></i> Clear Cache
            </button>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- Total Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Properties</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Featured Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Featured</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['featured_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-line text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Categories</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['categories'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="ri-folder-line text-xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <!-- Locations -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Locations</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['locations'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="ri-map-pin-line text-xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Properties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Properties</h3>
                    <a href="{{ route('admin.properties.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentProperties ?? [] as $property)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="ri-home-4-line text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $property->location->area_name ?? 'N/A' }} • 
                                        {{ number_format($property->price) }} ETB
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if($property->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Inactive</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No properties yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recentUsers ?? [] as $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No users yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection