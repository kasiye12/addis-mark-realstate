@extends('layouts.admin')

@section('title', 'Users Management')

@push('styles')
<style>
    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
    }
    .role-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    .user-card {
        transition: all 0.3s ease;
    }
    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-gray-600 mt-1">Manage all registered users and agents</p>
        </div>
        <div class="flex gap-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.users.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i class="ri-download-line"></i> Export
            </a>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="ri-add-line"></i> Add User
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-line text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Admins</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['admin'] }}</p>
                </div>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-admin-line text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Agents</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['agent'] }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-star-line text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Users</p>
                    <p class="text-xl font-bold text-gray-600">{{ $stats['user'] }}</p>
                </div>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-3-line text-gray-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Active</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Unverified</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['unverified'] }}</p>
                </div>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-error-warning-line text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">This Month</p>
                    <p class="text-xl font-bold text-indigo-600">{{ $stats['this_month'] }}</p>
                </div>
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="ri-calendar-line text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name, email or phone..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <select name="role" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                    <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agents</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Users</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-filter-line"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
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

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
        <div class="user-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="relative">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                               class="user-checkbox absolute -top-1 -left-1 w-5 h-5 rounded border-gray-300 z-10">
                        <img src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="user-avatar">
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white 
                                     {{ $user->is_active && $user->email_verified_at ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <h3 class="font-bold text-gray-900 truncate">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <span class="role-badge {{ $user->role_badge[0] }}">
                                {{ $user->role_badge[1] }}
                            </span>
                        </div>
                        
                        @if($user->phone)
                        <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                            <i class="ri-phone-line text-xs"></i> {{ $user->phone }}
                        </p>
                        @endif
                        
                        <div class="flex items-center gap-3 mt-3 text-sm">
                            <span class="flex items-center gap-1 text-gray-500">
                                <i class="ri-building-line"></i>
                                {{ $user->properties_count ?? 0 }} Properties
                            </span>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $user->status_badge[0] }}">
                                {{ $user->status_badge[1] }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <span class="text-xs text-gray-400">
                        <i class="ri-time-line mr-1"></i> Joined {{ $user->created_at->diffForHumans() }}
                    </span>
                    
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="p-2 text-gray-400 hover:text-blue-600 transition"
                           title="View Details">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="p-2 text-gray-400 hover:text-green-600 transition"
                           title="Edit">
                            <i class="ri-edit-line"></i>
                        </a>
                        
                        @if(!$user->email_verified_at)
                        <button onclick="verifyUser({{ $user->id }})" 
                                class="p-2 text-gray-400 hover:text-yellow-600 transition"
                                title="Verify Email">
                            <i class="ri-verified-badge-line"></i>
                        </button>
                        @endif
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="p-2 text-gray-400 hover:text-gray-600 transition">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                
                                <button onclick="toggleStatus({{ $user->id }})" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="ri-toggle-{{ $user->is_active ? 'fill' : 'line' }} mr-2"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                
                                <button onclick="changeRole({{ $user->id }})" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="ri-user-settings-line mr-2"></i> Change Role
                                </button>
                                
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser({{ $user->id }})" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <i class="ri-user-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first user.</p>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-1"></i> Add User
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Role Change Modal -->
<div id="roleModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4">
        <h3 class="text-lg font-bold mb-4">Change User Role</h3>
        <form id="roleForm">
            <input type="hidden" id="roleUserId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                <select id="userRole" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="admin">Admin</option>
                    <option value="agent">Agent</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                <button type="button" onclick="closeRoleModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Bulk Actions
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const checked = document.querySelectorAll('.user-checkbox:checked');
        const count = checked.length;
        selectedCount.textContent = count + ' selected';
        bulkActions.classList.toggle('hidden', count === 0);
    }
    
    function bulkDelete() {
        const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        
        if (ids.length === 0) return;
        if (!confirm('Are you sure you want to delete ' + ids.length + ' users?')) return;
        
        fetch('{{ route("admin.users.bulk-delete") }}', {
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
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
    
    // Role Modal
    const roleModal = document.getElementById('roleModal');
    const roleForm = document.getElementById('roleForm');
    
    function changeRole(id) {
        document.getElementById('roleUserId').value = id;
        roleModal.classList.remove('hidden');
        roleModal.classList.add('flex');
    }
    
    function closeRoleModal() {
        roleModal.classList.add('hidden');
        roleModal.classList.remove('flex');
    }
    
    roleForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('roleUserId').value;
        const role = document.getElementById('userRole').value;
        
        fetch(`/admin/users/${id}/assign-role`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });
</script>
@endsection