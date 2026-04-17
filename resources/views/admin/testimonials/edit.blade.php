@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Testimonial</h1>
            <p class="text-gray-600 mt-1">Update testimonial information</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <!-- Client Info -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Name *</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('client_name') border-red-300 @enderror">
                    @error('client_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating *</label>
                    <select name="rating" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars - Excellent</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars - Very Good</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars - Good</option>
                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars - Fair</option>
                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star - Poor</option>
                    </select>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="client_position" value="{{ old('client_position', $testimonial->client_position) }}" placeholder="e.g., CEO"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="client_company" value="{{ old('client_company', $testimonial->client_company) }}" placeholder="Company name"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Current Image -->
            @if($testimonial->client_image)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/' . $testimonial->client_image) }}" 
                         alt="{{ $testimonial->client_name }}" 
                         class="w-20 h-20 rounded-lg object-cover border border-gray-200">
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
                <input type="file" name="client_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current photo. Max size: 2MB</p>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Testimonial Content *</label>
                <textarea name="content" rows="5" required
                          class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 @error('content') border-red-300 @enderror"
                          placeholder="What did the client say?">{{ old('content', $testimonial->content) }}</textarea>
                <p class="text-sm text-gray-500 mt-1"><span id="charCount">{{ strlen($testimonial->content) }}</span>/1000 characters</p>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="ri-save-line"></i> Update Testimonial
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('content').addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });
</script>
@endsection