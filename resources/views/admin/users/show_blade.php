@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.1);
    }
    .property-thumb {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
    }
    .timeline-item {
        position: relative;
        padding-left: 24px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #3b82f6;
    }
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 18px;
        width: 2px;
        height: calc(100% + 8px);
        background: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-edit-line"></i> Edit User
            </a>
            @if($user->id !== auth()->id())
            <button onclick="deleteUser({{ $user->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                <i class="ri-delete-bin-line"></i> Delete
            </button>
            @endif
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <!-- Cover Background -->
        <div class="h-32 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
        
        <div class="px-6 pb-6">
            <div class="flex flex-wrap items-end gap-6 -mt-12">
                <!-- Avatar -->
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg object-cover">
                    <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white 
                                 {{ $user->is_active && $user->email_verified_at ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                </div>
                
                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-4 mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->role_badge[0] }}">
                            {{ $user->role_badge[1] }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->status_badge[0] }}">
                            {{ $user->status_badge[1] }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-6 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="ri-mail-line text-blue-600"></i>
                            <span>{{ $user->email }}</span>
                            @if(!$user->email_verified_at)
                                <span class="text-yellow-600 text-xs">(Unverified)</span>
                            @endif
                        </div>
                        
                        @if($user->phone)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="ri-phone-line text-blue-600"></i>
                            <span>{{ $user->phone }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="ri-calendar-line text-blue-600"></i>
                            <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex gap-2">
                    @if(!$user->email_verified_at)
                    <button onclick="verifyUser({{ $user->id }})" 
                            class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition flex items-center gap-2">
                        <i class="ri-verified-badge-line"></i> Verify Email
                    </button>
                    @endif
                    
                    @if($user->id !== auth()->id())
                    <button onclick="toggleStatus({{ $user->id }})" 
                            class="px-4 py-2 {{ $user->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition flex items-center gap-2">
                        <i class="ri-toggle-{{ $user->is_active ? 'fill' : 'line' }}"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    @endif
                </div>
            </div>
            
            @if($user->bio)
            <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                <h3 class="text-sm font-medium text-gray-700 mb-2">About</h3>
                <p class="text-gray-600">{{ $user->bio }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Properties</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['properties_count'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="ri-building-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Properties</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active_properties'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="ri-check-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Featured Properties</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['featured_properties'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="ri-star-line text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_views']) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="ri-eye-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties & Activity -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Properties List -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Recent Properties</h3>
                    @if($user->properties_count > 0)
                    <a href="{{ route('admin.properties.index', ['user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-700">
                        View All <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($user->properties as $property)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex gap-4">
                        @if($property->primary_image)
                        <img src="{{ asset('storage/' . $property->primary_image) }}" 
                             alt="{{ $property->title }}" 
                             class="property-thumb">
                        @else
                        <div class="property-thumb bg-gray-200 flex items-center justify-center">
                            <i class="ri-home-4-line text-gray-400"></i>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-1">
                                <a href="{{ route('properties.show', $property->slug) }}" target="_blank" 
                                   class="font-medium text-gray-900 hover:text-blue-600 transition">
                                    {{ $property->title }}
                                </a>
                                <span class="text-sm font-semibold text-blue-600">
                                    ETB {{ number_format($property->price) }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-2">
                                <i class="ri-map-pin-line text-xs mr-1"></i>
                                {{ $property->location->area_name ?? 'N/A' }}
                            </p>
                            
                            <div class="flex items-center gap-4">
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $property->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $property->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <i class="ri-eye-line"></i> {{ $property->views }} views
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $property->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="ri-building-line text-4xl mb-2 opacity-50"></i>
                    <p>No properties listed yet.</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">
                    <i class="ri-information-line mr-2"></i>Account Information
                </h3>
                
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">User ID</dt>
                        <dd class="font-mono text-sm">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Role</dt>
                        <dd class="font-medium">{{ ucfirst($user->role) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email Status</dt>
                        <dd>
                            @if($user->email_verified_at)
                                <span class="text-green-600"><i class="ri-checkbox-circle-fill mr-1"></i>Verified</span>
                            @else
                                <span class="text-yellow-600"><i class="ri-error-warning-fill mr-1"></i>Pending</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Account Status</dt>
                        <dd>
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                <i class="ri-{{ $user->is_active ? 'check-line' : 'close-line' }} mr-1"></i>
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Created</dt>
                        <dd>{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Last Updated</dt>
                        <dd>{{ $user->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
            
            <!-- Activity Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">
                    <i class="ri-history-line mr-2"></i>Recent Activity
                </h3>
                
                <div class="space-y-4">
                    @if($user->properties_count > 0)
                    <div class="timeline-item">
                        <p class="text-sm font-medium text-gray-900">First property listed</p>
                        <p class="text-xs text-gray-500">{{ $user->properties->last()->created_at->diffForHumans() }}</p>
                    </div>
                    @endif
                    
                    @if($user->email_verified_at)
                    <div class="timeline-item">
                        <p class="text-sm font-medium text-gray-900">Email verified</p>
                        <p class="text-xs text-gray-500">{{ $user->email_verified_at->diffForHumans() }}</p>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <p class="text-sm font-medium text-gray-900">Account created</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteUser(id) {
        if (!confirm('Are you sure you want to delete this user? All associated data will be lost.')) return;
        
        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.users.index") }}';
            } else {
                alert(data.message);
            }
        });
    }
    
    function toggleStatus(id) {
        fetch(`/admin/users/${id}/toggle-status`, {
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
            } else {
                alert(data.message);
            }
        });
    }
    
    function verifyUser(id) {
        fetch(`/admin/users/${id}/verify`, {
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