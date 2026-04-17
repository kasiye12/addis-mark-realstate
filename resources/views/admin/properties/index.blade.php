@extends('layouts.admin')

@section('title', 'Properties Management')

@push('styles')
<style>
    .property-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Properties Management</h1>
            <p class="text-gray-600 mt-1">Manage all property listings</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.properties.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i class="ri-download-line"></i> Export
            </a>
            <button onclick="document.getElementById('import-file').click()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2">
                <i class="ri-upload-line"></i> Import
            </button>
            <a href="{{ route('admin.properties.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-add-line"></i> Add Property
            </a>
        </div>
    </div>

    <!-- Import Form (Hidden) -->
    <form id="import-form" action="{{ route('admin.properties.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="file" id="import-file" accept=".csv" onchange="document.getElementById('import-form').submit()">
    </form>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-sm text-gray-500">Featured</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['featured'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-sm text-gray-500">For Sale</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['for_sale'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-sm text-gray-500">For Rent</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['for_rent'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.properties.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search properties..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                </select>
            </div>
            <div>
                <select name="price_type" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">All Types</option>
                    <option value="sale" {{ request('price_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="rent" {{ request('price_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-filter-line"></i> Filter
                </button>
                <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-gray-50 rounded-lg p-3 mb-4 hidden">
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">Bulk Actions:</span>
            <button onclick="bulkAction('activate')" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-sm">Activate</button>
            <button onclick="bulkAction('deactivate')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Deactivate</button>
            <button onclick="bulkAction('feature')" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-sm">Feature</button>
            <button onclick="bulkAction('unfeature')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Unfeature</button>
            <button onclick="bulkAction('delete')" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm">Delete</button>
            <span id="selected-count" class="text-sm text-gray-500 ml-auto">0 selected</span>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="w-10 p-4">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300">
                        </th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Image</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Title</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Category</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Location</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Price</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Type</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($properties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">
                            <input type="checkbox" name="property_ids[]" value="{{ $property->id }}" class="property-checkbox rounded border-gray-300">
                        </td>
                        <td class="p-4">
                            @if($property->primary_image)
                                <img src="{{ asset('storage/' . $property->primary_image) }}" alt="{{ $property->title }}" class="property-thumbnail">
                            @else
                                <div class="property-thumbnail bg-gray-200 flex items-center justify-center">
                                    <i class="ri-home-4-line text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.properties.show', $property) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                {{ Str::limit($property->title, 40) }}
                            </a>
                            @if($property->is_featured)
                                <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Featured</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600">{{ $property->category->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-600">{{ $property->location->area_name ?? 'N/A' }}</td>
                        <td class="p-4 font-medium text-gray-900">{{ number_format($property->price) }} ETB</td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $property->price_type === 'sale' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ ucfirst($property->price_type) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <button onclick="toggleStatus({{ $property->id }})" class="toggle-status">
                                <span class="px-2 py-1 text-xs rounded-full {{ $property->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $property->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </button>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600" title="View">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.properties.edit', $property) }}" class="p-2 text-gray-500 hover:text-green-600" title="Edit">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button onclick="toggleFeatured({{ $property->id }})" class="p-2 {{ $property->is_featured ? 'text-yellow-500' : 'text-gray-400' }} hover:text-yellow-600" title="{{ $property->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <i class="ri-star-{{ $property->is_featured ? 'fill' : 'line' }}"></i>
                                </button>
                                <button onclick="deleteProperty({{ $property->id }})" class="p-2 text-gray-500 hover:text-red-600" title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-gray-500">
                            <i class="ri-building-line text-4xl mb-2"></i>
                            <p>No properties found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($properties->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $properties->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    const propertyCheckboxes = document.querySelectorAll('.property-checkbox');
    const selectAll = document.getElementById('select-all');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkActions() {
        const checked = document.querySelectorAll('.property-checkbox:checked');
        const count = checked.length;
        selectedCount.textContent = count + ' selected';
        bulkActions.classList.toggle('hidden', count === 0);
    }

    selectAll?.addEventListener('change', function() {
        propertyCheckboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    propertyCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    function bulkAction(action) {
        const ids = Array.from(document.querySelectorAll('.property-checkbox:checked')).map(cb => cb.value);
        
        if (ids.length === 0) return;
        
        if (action === 'delete' && !confirm('Are you sure you want to delete ' + ids.length + ' properties?')) {
            return;
        }

        fetch('{{ route("admin.properties.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action, ids })
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

    function toggleStatus(id) {
        fetch(`/admin/properties/${id}/toggle-status`, {
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

    function toggleFeatured(id) {
        fetch(`/admin/properties/${id}/toggle-featured`, {
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

    function deleteProperty(id) {
        if (!confirm('Are you sure you want to delete this property?')) return;

        fetch(`/admin/properties/${id}`, {
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