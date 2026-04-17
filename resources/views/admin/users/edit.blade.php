@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@push('styles')
<style>
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .section-title {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin-bottom: 16px;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.show', $user) }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-gray-600 mt-1">Update user information for {{ $user->name }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Profile Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="section-title"><i class="ri-user-line mr-2"></i>Profile Information</h2>
                
                <div class="flex flex-wrap items-start gap-8">
                    <!-- Avatar -->
                    <div class="text-center">
                        <img src="{{ old('avatar') ? asset('storage/' . old('avatar')) : $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="avatar-preview mb-3"
                             id="avatarPreview">
                        <label class="cursor-pointer">
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                                <i class="ri-upload-line mr-1"></i> Change Photo
                            </span>
                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                        </label>
                        @error('avatar')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Basic Info -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea name="bio" rows="3" 
                              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>
            
            <!-- Account Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="section-title"><i class="ri-settings-4-line mr-2"></i>Account Settings</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                                       class="rounded border-gray-300 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Active Account</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Password Change -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Change Password</h3>
                    <p class="text-sm text-gray-500 mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone -->
            @if($user->id !== auth()->id())
            <div class="bg-red-50 rounded-2xl border border-red-200 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-red-800 font-semibold mb-1">Danger Zone</h3>
                        <p class="text-red-600 text-sm">Once you delete a user, there is no going back. Please be certain.</p>
                    </div>
                    <button type="button" onclick="deleteUser({{ $user->id }})" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="ri-delete-bin-line mr-1"></i> Delete User
                    </button>
                </div>
            </div>
            @endif
            
            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-4">
                <div class="text-sm text-gray-500">
                    <i class="ri-time-line mr-1"></i> Last updated: {{ $user->updated_at->format('M d, Y h:i A') }}
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="ri-save-line"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function deleteUser(id) {
        if (!confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.')) return;
        
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
</script>
@endsection