@extends('layouts.admin')

@section('title', 'Create New Post')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Post</h1>
            <p class="text-gray-600 mt-1">Write a new blog post</p>
        </div>
        <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-xl p-6 border">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border rounded-lg @error('title') border-red-300 @enderror">
                    @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Excerpt -->
                <div class="bg-white rounded-xl p-6 border">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                    <textarea name="excerpt" rows="3" class="w-full px-4 py-2 border rounded-lg">{{ old('excerpt') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Brief summary of the post</p>
                </div>
                
                <!-- Content -->
                <div class="bg-white rounded-xl p-6 border">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                    <textarea name="content" rows="15" required class="w-full px-4 py-2 border rounded-lg @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                    @error('content')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Publishing -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold mb-4">Publishing</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author *</label>
                            <select name="author_id" required class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Post Type *</label>
                            <select name="post_type" required class="w-full px-4 py-2 border rounded-lg">
                                <option value="blog" {{ old('post_type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                <option value="tip" {{ old('post_type') == 'tip' ? 'selected' : '' }}>Real Estate Tip</option>
                                <option value="market_update" {{ old('post_type') == 'market_update' ? 'selected' : '' }}>Market Update</option>
                                <option value="investment" {{ old('post_type') == 'investment' ? 'selected' : '' }}>Investment Advice</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="real estate, investment, tips" class="w-full px-4 py-2 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Comma separated</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reading Time (minutes)</label>
                            <input type="number" name="reading_time" value="{{ old('reading_time', 5) }}" min="1" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>
                
                <!-- Featured Image -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold mb-4">Featured Image</h3>
                    <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <!-- Options -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold mb-4">Options</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Publish immediately</span>
                        </label>
                        
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Featured post</span>
                        </label>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="bg-white rounded-xl p-6 border">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="ri-save-line mr-1"></i> Save Post
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection