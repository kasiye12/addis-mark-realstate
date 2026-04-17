@extends('layouts.app')

@section('title', 'Profile Settings')

@push('styles')
<style>
    .avatar-upload {
        position: relative;
        width: 120px;
        height: 120px;
    }
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 20px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .avatar-overlay {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 36px;
        height: 36px;
        background: #2563eb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid white;
    }
    .avatar-overlay:hover {
        background: #1d4ed8;
        transform: scale(1.05);
    }
    .tab-button {
        padding: 12px 20px;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .tab-button.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }
    .tab-button:hover {
        color: #2563eb;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .danger-zone {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="ri-check-line mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-wrap items-center gap-6">
                <div class="avatar-upload">
                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=120&background=2563eb&color=fff' }}" 
                         alt="{{ $user->name }}" 
                         class="avatar-preview"
                         id="avatarPreview">
                    <label for="avatarInput" class="avatar-overlay">
                        <i class="ri-camera-line text-lg"></i>
                    </label>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="uploadAvatar(this)">
                </div>
                
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="ri-user-line mr-1"></i> 
                        {{ ucfirst($user->role ?? 'User') }}
                        <span class="mx-2">•</span>
                        <i class="ri-calendar-line mr-1"></i>
                        Member since {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 px-6 flex gap-2">
                <button class="tab-button active" data-tab="profile">Profile Information</button>
                <button class="tab-button" data-tab="password">Password</button>
                <button class="tab-button" data-tab="danger">Danger Zone</button>
            </div>

            <!-- Profile Tab -->
            <div id="profile-tab" class="tab-content active p-6">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5 max-w-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if(!$user->hasVerifiedEmail())
                                <p class="text-yellow-600 text-sm mt-2">
                                    <i class="ri-error-warning-line mr-1"></i>
                                    Your email is not verified. 
                                    <button type="button" onclick="resendVerification()" class="text-blue-600 hover:underline">Resend verification</button>
                                </p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" rows="3" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Tell us a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Password Tab -->
            <div id="password-tab" class="tab-content p-6">
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5 max-w-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-300 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Danger Zone Tab -->
            <div id="danger-tab" class="tab-content p-6">
                <div class="max-w-lg">
                    <div class="danger-zone p-6">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Delete Account</h3>
                        <p class="text-red-600 text-sm mb-4">
                            Once you delete your account, there is no going back. All your data will be permanently removed.
                        </p>
                        
                        <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="ri-delete-bin-line mr-2"></i> Delete My Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-3">Are you absolutely sure?</h3>
        <p class="text-gray-600 mb-6">
            This action cannot be undone. All your data will be permanently deleted.
        </p>
        
        <form action="{{ route('profile.destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Enter your password to confirm</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                    Delete Account
                </button>
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab switching
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tab = this.dataset.tab;
            
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(tab + '-tab').classList.add('active');
        });
    });

    // Avatar upload
    function uploadAvatar(input) {
        if (!input.files || !input.files[0]) return;
        
        const formData = new FormData();
        formData.append('avatar', input.files[0]);
        
        fetch('{{ route("profile.avatar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avatarPreview').src = data.avatar_url + '?t=' + Date.now();
                showToast('Avatar updated successfully!');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(() => {
            showToast('Failed to upload avatar', 'error');
        });
    }

    // Delete confirmation
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 px-5 py-3 rounded-lg text-white text-sm font-medium shadow-lg z-50 ${type === 'error' ? 'bg-red-600' : 'bg-green-600'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }

    // Resend verification
    function resendVerification() {
        fetch('{{ route("verification.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            showToast('Verification email sent!');
        });
    }
</script>
@endsection