@extends('layouts.admin')

@section('title', 'Locations Management')

@push('styles')
<style>
    .location-card {
        transition: all 0.2s ease;
    }
    .location-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1);
    }
    .map-preview {
        height: 120px;
        background: #f3f4f6;
        border-radius: 8px;
        overflow: hidden;
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
            <a href="{{ route('admin.locations.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="ri-download-line mr-1"></i> Export
            </a>
            <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="ri-add-line mr-1"></i> Add Location
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Total Locations</p>
            <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Popular Areas</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['popular'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">With Properties</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['with_properties'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Cities</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['cities'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 border mb-6">
        <form method="GET" class="grid grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search locations..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">
            <select name="city" class="px-4 py-2 border rounded-lg">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            <a href="{{ route('admin.locations.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg text-center">Reset</a>
        </form>
    </div>

    <!-- Locations Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($locations as $location)
        <div class="location-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($location->latitude && $location->longitude)
            <div class="map-preview">
                <iframe width="100%" height="120" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                        src="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}&z=13&output=embed"></iframe>
            </div>
            @else
            <div class="map-preview flex items-center justify-center">
                <i class="ri-map-pin-line text-3xl text-gray-400"></i>
            </div>
            @endif
            
            <div class="p-5">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $location->area_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $location->sub_city ? $location->sub_city . ', ' : '' }}{{ $location->city }}</p>
                    </div>
                    @if($location->is_popular)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Popular</span>
                    @endif
                </div>
                
                <div class="flex items-center gap-4 text-sm mb-4">
                    <span class="text-gray-600">
                        <i class="ri-building-line mr-1"></i> {{ $location->properties_count }} Properties
                    </span>
                </div>
                
                <div class="flex items-center justify-between pt-3 border-t">
                    <a href="{{ route('properties.location', $location->slug) }}" target="_blank" class="text-blue-600 text-sm">
                        <i class="ri-external-link-line mr-1"></i> View
                    </a>
                    <div class="flex gap-1">
                        <button onclick="togglePopular({{ $location->id }})" class="p-2 text-gray-400 hover:text-yellow-600">
                            <i class="ri-star-{{ $location->is_popular ? 'fill' : 'line' }}"></i>
                        </button>
                        <button onclick="editLocation({{ $location }})" class="p-2 text-gray-400 hover:text-blue-600">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button onclick="deleteLocation({{ $location->id }})" class="p-2 text-gray-400 hover:text-red-600">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="ri-map-pin-line text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No locations found.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $locations->links() }}</div>
</div>

<!-- Location Modal -->
<div id="locationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-xl">
        <div class="px-6 py-4 border-b">
            <h2 id="modalTitle" class="text-xl font-bold">Add Location</h2>
        </div>
        <form id="locationForm">
            <input type="hidden" id="locationId">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Area Name *</label>
                    <input type="text" id="areaName" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">City *</label>
                    <input type="text" id="city" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Sub City</label>
                    <input type="text" id="subCity" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Latitude</label>
                        <input type="number" id="latitude" step="any" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Longitude</label>
                        <input type="number" id="longitude" step="any" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="isPopular" class="rounded">
                    <span class="text-sm">Mark as popular location</span>
                </label>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('locationModal');
    const form = document.getElementById('locationForm');
    
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Add Location';
        document.getElementById('locationId').value = '';
        form.reset();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    function editLocation(location) {
        document.getElementById('modalTitle').textContent = 'Edit Location';
        document.getElementById('locationId').value = location.id;
        document.getElementById('areaName').value = location.area_name;
        document.getElementById('city').value = location.city;
        document.getElementById('subCity').value = location.sub_city || '';
        document.getElementById('latitude').value = location.latitude || '';
        document.getElementById('longitude').value = location.longitude || '';
        document.getElementById('isPopular').checked = location.is_popular;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('locationId').value;
        const url = id ? `/admin/locations/${id}` : '/admin/locations';
        const method = id ? 'PUT' : 'POST';
        
        const data = {
            area_name: document.getElementById('areaName').value,
            city: document.getElementById('city').value,
            sub_city: document.getElementById('subCity').value || null,
            latitude: document.getElementById('latitude').value || null,
            longitude: document.getElementById('longitude').value || null,
            is_popular: document.getElementById('isPopular').checked,
            _token: '{{ csrf_token() }}'
        };
        
        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(d => { if(d.success) location.reload(); });
    });
    
    function togglePopular(id) {
        fetch(`/admin/locations/${id}/toggle-popular`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
    }
    
    function deleteLocation(id) {
        if (!confirm('Delete this location?')) return;
        fetch(`/admin/locations/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
    }
    
    modal.addEventListener('click', function(e) { if(e.target === modal) closeModal(); });
</script>
@endsection