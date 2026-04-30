@extends('layouts.admin')

@section('title', 'Create New Post')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .gallery-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    .gallery-preview img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    .image-preview-container {
        position: relative;
    }
    .remove-image {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Post</h1>
            <p class="text-gray-600 mt-1">Write a new blog post</p>
        </div>
        <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="ri-arrow-left-line mr-1"></i> Back to Posts
        </a>
    </div>

    <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data" id="blogPostForm">
        @csrf
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                           placeholder="Enter post title">
                    @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Excerpt -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                    <textarea name="excerpt" rows="3" 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Brief summary of the post (optional)">{{ old('excerpt') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">A short description shown in post listings. Max 500 characters.</p>
                    @error('excerpt')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Content -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="20" required 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror"
                              placeholder="Write your post content here...">{{ old('content') }}</textarea>
                    @error('content')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Gallery Images -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="gallery_images[]" multiple accept="image/*" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewGalleryImages(event)">
                    <p class="text-xs text-gray-500 mt-1">You can select multiple images. Supported formats: JPG, PNG, GIF, WebP. Max 5MB each.</p>
                    <div id="galleryPreview" class="gallery-preview mt-4"></div>
                    @error('gallery_images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Video URL -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
                    <p class="text-xs text-gray-500 mt-1">Link to a YouTube, Vimeo, or other video platform.</p>
                    @error('video_url')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- SEO Meta Fields -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SEO title (defaults to post title if empty)">
                            <p class="text-xs text-gray-500 mt-1">Recommended length: 50-60 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="SEO description (defaults to excerpt if empty)">{{ old('meta_description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Recommended length: 150-160 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="keyword1, keyword2, keyword3">
                            <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing Settings -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-settings-line mr-2"></i> Publishing
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Author <span class="text-red-500">*</span></label>
                            <select name="author_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('author_id') border-red-300 @enderror">
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">No Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Post Type <span class="text-red-500">*</span></label>
                            <select name="post_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('post_type') border-red-300 @enderror">
                                @foreach($postTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('post_type', 'blog') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('post_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="real estate, investment, tips">
                            <p class="text-xs text-gray-500 mt-1">Separate tags with commas</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                            <input type="number" name="reading_time" value="{{ old('reading_time', 5) }}" min="1" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty for auto-calculation</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Publication</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to publish immediately when toggled</p>
                        </div>
                    </div>
                </div>
                
                <!-- Featured Image -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-image-line mr-2"></i> Featured Image
                    </h3>
                    <input type="file" name="featured_image" accept="image/*" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewFeaturedImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x630px. Max 5MB.</p>
                    <div id="featuredImagePreview" class="mt-3"></div>
                    @error('featured_image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Options -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-checkbox-circle-line mr-2"></i> Options
                    </h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Publish immediately</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Mark as featured</span>
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 space-y-3">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition flex items-center justify-center">
                        <i class="ri-save-line mr-2"></i> Save Post
                    </button>
                    
                    <button type="button" onclick="saveAsDraft()" class="w-full px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 transition flex items-center justify-center">
                        <i class="ri-draft-line mr-2"></i> Save as Draft
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 for better select inputs
    $(document).ready(function() {
        $('select').select2({
            theme: 'classic',
            width: '100%'
        });
    });

    // Preview featured image
    function previewFeaturedImage(event) {
        const preview = document.getElementById('featuredImagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="Featured image preview" class="w-full h-48 object-cover rounded-lg">
                        <button type="button" onclick="removeFeaturedImage()" class="remove-image">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    // Remove featured image preview
    function removeFeaturedImage() {
        document.querySelector('input[name="featured_image"]').value = '';
        document.getElementById('featuredImagePreview').innerHTML = '';
    }

    // Preview gallery images
    function previewGalleryImages(event) {
        const preview = document.getElementById('galleryPreview');
        const files = event.target.files;
        
        let html = '';
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                html += `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="Gallery image ${i + 1}">
                        <button type="button" onclick="this.parentElement.remove()" class="remove-image">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                `;
                preview.innerHTML = html;
            }
            reader.readAsDataURL(files[i]);
        }
    }

    // Save as draft function
    function saveAsDraft() {
        // Uncheck publish checkbox
        document.querySelector('input[name="is_published"]').checked = false;
        // Submit the form
        document.getElementById('blogPostForm').submit();
    }

    // Auto-generate excerpt from content
    document.querySelector('textarea[name="content"]').addEventListener('input', function() {
        const excerptField = document.querySelector('textarea[name="excerpt"]');
        if (!excerptField.value) {
            const text = this.value.replace(/<[^>]*>/g, '').substring(0, 200);
            excerptField.placeholder = text;
        }
    });

    // Auto-calculate reading time
    function updateReadingTime() {
        const content = document.querySelector('textarea[name="content"]').value;
        const wordsPerMinute = 200;
        const wordCount = content.replace(/<[^>]*>/g, '').trim().split(/\s+/).length;
        const readingTime = Math.max(1, Math.ceil(wordCount / wordsPerMinute));
        
        const readingTimeField = document.querySelector('input[name="reading_time"]');
        if (!readingTimeField.value || readingTimeField.dataset.auto === 'true') {
            readingTimeField.value = readingTime;
            readingTimeField.dataset.auto = 'true';
        }
    }

    document.querySelector('textarea[name="content"]').addEventListener('input', updateReadingTime);
    document.querySelector('input[name="reading_time"]').addEventListener('input', function() {
        this.dataset.auto = 'false';
    });
</script>
@endpush