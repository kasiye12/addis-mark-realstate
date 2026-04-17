@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Team Member</h1>
            <p class="text-gray-600 mt-1">Update team member information</p>
        </div>
        <a href="{{ route('admin.team.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.team.update', $teamMember) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $teamMember->name) }}" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                    <input type="text" name="position" value="{{ old('position', $teamMember->position) }}" required placeholder="e.g., Senior Real Estate Agent"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('position') border-red-300 @enderror">
                    @error('position')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $teamMember->email) }}"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $teamMember->phone) }}"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Short biography...">{{ old('bio', $teamMember->bio) }}</textarea>
            </div>

            <!-- Current Image -->
            @if($teamMember->image)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/' . $teamMember->image) }}" 
                         alt="{{ $teamMember->name }}" 
                         class="w-24 h-24 rounded-xl object-cover border border-gray-200">
                    <label class="flex items-center gap-2 text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" class="rounded">
                        Remove current photo
                    </label>
                </div>
            </div>
            @endif

            <!-- New Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Update Photo</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current photo. Max size: 2MB</p>
            </div>

            <!-- Social Links -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Social Links</label>
                @php
                    $socialLinks = is_array($teamMember->social_links) ? $teamMember->social_links : json_decode($teamMember->social_links, true) ?? [];
                @endphp
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">Facebook</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $socialLinks['facebook'] ?? '') }}" placeholder="https://facebook.com/..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Twitter/X</label>
                        <input type="url" name="twitter" value="{{ old('twitter', $socialLinks['twitter'] ?? '') }}" placeholder="https://twitter.com/..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">LinkedIn</label>
                        <input type="url" name="linkedin" value="{{ old('linkedin', $socialLinks['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Instagram</label>
                        <input type="url" name="instagram" value="{{ old('instagram', $socialLinks['instagram'] ?? '') }}" placeholder="https://instagram.com/..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>
            </div>

            <!-- Sort Order & Status -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $teamMember->sort_order) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }} class="rounded">
                        <span class="text-sm text-gray-700">Active (visible on website)</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="ri-save-line"></i> Update Member
                </button>
                <a href="{{ route('admin.team.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection