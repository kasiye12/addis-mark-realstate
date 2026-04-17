@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-1">Here's what's happening with your real estate business today.</p>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.properties.create') }}" class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
            <i class="ri-add-line"></i> Add Property
        </a>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 shadow-sm">
            <i class="ri-user-add-line"></i> Add User
        </a>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-2 shadow-sm">
            <i class="ri-folder-add-line"></i> Manage Categories
        </a>
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2 shadow-sm">
            <i class="ri-settings-line"></i> Settings
        </a>
        <form action="{{ route('admin.quick-action') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="action" value="clear_cache">
            <button type="submit" class="px-4 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2 shadow-sm">
                <i class="ri-delete-back-line"></i> Clear Cache
            </button>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- Total Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Properties</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Featured Properties -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Featured</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['featured_properties'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-line text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- For Sale -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">For Sale</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['for_sale'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-price-tag-3-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- For Rent -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">For Rent</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['for_rent'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="ri-key-line text-xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <!-- Properties by Category Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-pie-chart-line text-blue-600"></i>
                Properties by Category
            </h3>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Properties by Location Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-bar-chart-line text-blue-600"></i>
                Properties by Location
            </h3>
            <div class="h-64">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Properties & Users Row -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Properties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-building-line text-blue-600"></i>
                        Recent Properties
                    </h3>
                    <a href="{{ route('admin.properties.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        View All <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    @forelse($recentProperties ?? [] as $property)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition">
                                    @if($property->primary_image)
                                        @php
                                            $imageUrl = \Storage::disk('public')->exists($property->primary_image) 
                                                ? route('file.show', ['path' => $property->primary_image]) 
                                                : asset('images/default-property.jpg');
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <i class="ri-home-4-line text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">
                                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank">
                                            {{ Str::limit($property->title, 30) }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $property->location->area_name ?? 'N/A' }} • 
                                        ETB {{ number_format($property->price) }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if($property->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Inactive</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ri-building-line text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">No properties yet.</p>
                            <a href="{{ route('admin.properties.create') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">
                                Add your first property
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users & Popular Properties -->
        <div class="space-y-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ri-user-line text-blue-600"></i>
                            Recent Users
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            View All <i class="ri-arrow-right-line ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        @forelse($recentUsers ?? [] as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $user->role_badge[0] }}">
                                        {{ $user->role_badge[1] }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No users yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Popular Properties -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-fire-line text-orange-500"></i>
                        Most Viewed Properties
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        @forelse($popularProperties ?? [] as $property)
                            <div class="flex items-center justify-between group">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">
                                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank">
                                            {{ Str::limit($property->title, 35) }}
                                        </a>
                                    </p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-sm text-gray-500">
                                            <i class="ri-eye-line mr-1"></i> {{ number_format($property->views) }} views
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i class="ri-map-pin-line mr-1"></i> {{ $property->location->area_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('properties.show', $property->slug) }}" target="_blank" 
                                   class="p-2 text-gray-400 hover:text-blue-600 transition">
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No views recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
        const categoryData = @json($propertiesByCategory ?? []);
        
        if (categoryCtx && categoryData.length > 0) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(item => item.name),
                    datasets: [{
                        data: categoryData.map(item => item.properties_count),
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Location Chart
        const locationCtx = document.getElementById('locationChart')?.getContext('2d');
        const locationData = @json($propertiesByLocation ?? []);
        
        if (locationCtx && locationData.length > 0) {
            new Chart(locationCtx, {
                type: 'bar',
                data: {
                    labels: locationData.map(item => item.area_name),
                    datasets: [{
                        label: 'Properties',
                        data: locationData.map(item => item.properties_count),
                        backgroundColor: '#3b82f6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                display: true,
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush