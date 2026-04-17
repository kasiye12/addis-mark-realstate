@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
        <p class="text-gray-600 mt-1">Create a new user account</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <!-- Avatar -->
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="ri-user-line text-4xl text-gray-400"></i>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg @error('name') border-red-300 @enderror">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg @error('email') border-red-300 @enderror">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg @error('password') border-red-300 @enderror">
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                    <select name="role" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg">{{ old('bio') }}</textarea>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded">
                    <span class="text-sm">Active Account</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="email_verified_at" value="1" {{ old('email_verified_at') ? 'checked' : '' }} class="rounded">
                    <span class="text-sm">Email Verified</span>
                </label>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection