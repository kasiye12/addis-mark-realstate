@extends('layouts.admin')

@section('title', 'Edit Post - ' . $post->title)

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
    .current-image {
        position: relative;
    }
    .remove-current-image {
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
            <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
            <p class="text-gray-600 mt-1">
                Editing: <span class="font-medium">{{ $post->title }}</span>
                @if($post->is_published)
                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                @else
                    <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Draft</span>
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.blog.posts.preview', $post) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition" target="_blank">
                <i class="ri-eye-line mr-1"></i> Preview
            </a>
            <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="ri-arrow-left-line mr-1"></i> Back to Posts
            </a>
        </div>
    </div>

    <form action="{{ route('admin.blog.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="blogPostForm">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                           placeholder="Enter post title">
                    @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Excerpt -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                    <textarea name="excerpt" rows="3" 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Brief summary of the post (optional)">{{ old('excerpt', $post->excerpt) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">A short description shown in post listings. Max 500 characters.</p>
                    @error('excerpt')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- Content -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="20" required 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror"
                              placeholder="Write your post content here...">{{ old('content', $post->content) }}</textarea>
                    @error('content')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Gallery Images -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    
                    @if($post->gallery_images && count($post->gallery_images) > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Current Gallery Images</h4>
                        <div class="gallery-preview">
                            @foreach($post->gallery_images as $index => $image)
                            <div class="current-image">
                                <img src="{{ route('file.show', ['path' => $image]) }}" alt="Gallery image {{ $index + 1 }}">
                                <button type="button" onclick="removeCurrentImage('{{ $image }}', this)" class="remove-current-image">
                                    <i class="ri-close-line"></i>
                                </button>
                                <input type="hidden" name="remove_gallery_images[]" value="{{ $image }}" disabled>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <input type="file" name="gallery_images[]" multiple accept="image/*" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewNewGalleryImages(event)">
                    <p class="text-xs text-gray-500 mt-1">Add more images. Supported formats: JPG, PNG, GIF, WebP. Max 5MB each.</p>
                    <div id="newGalleryPreview" class="gallery-preview mt-4"></div>
                    @error('gallery_images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Video URL -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $post->video_url) }}" 
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
                            <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SEO title (defaults to post title if empty)">
                            <p class="text-xs text-gray-500 mt-1">Recommended length: 50-60 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="SEO description (defaults to excerpt if empty)">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Recommended length: 150-160 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="keyword1, keyword2, keyword3">
                            <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Post Statistics -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-bar-chart-line mr-2"></i> Statistics
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($post->views) }}</div>
                            <div class="text-xs text-gray-600">Views</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $post->reading_time }}m</div>
                            <div class="text-xs text-gray-600">Read Time</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $post->created_at->format('M d') }}</div>
                            <div class="text-xs text-gray-600">Created</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $post->updated_at->format('M d') }}</div>
                            <div class="text-xs text-gray-600">Updated</div>
                        </div>
                    </div>
                </div>

                <!-- Publishing Settings -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-settings-line mr-2"></i> Publishing
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Author <span class="text-red-500">*</span></label>
                            <select name="author_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('author_id') border-red-300 @enderror">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', $post->author_id) == $author->id ? 'selected' : '' }}>
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Post Type <span class="text-red-500">*</span></label>
                            <select name="post_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('post_type') border-red-300 @enderror">
                                @foreach($postTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('post_type', $post->post_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('post_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : $post->tags) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="real estate, investment, tips">
                            <p class="text-xs text-gray-500 mt-1">Separate tags with commas</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                            <input type="number" name="reading_time" value="{{ old('reading_time', $post->reading_time) }}" min="1" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Auto-calculated if not set</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Publication</label>
                            <input type="datetime-local" name="published_at" 
                                   value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Current: {{ $post->published_at ? $post->published_at->format('M d, Y H:i') : 'Not scheduled' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Featured Image -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-image-line mr-2"></i> Featured Image
                    </h3>
                    @if($post->featured_image)
                    <div class="mb-3">
                        <img src="{{ $post->featured_image_url }}" alt="Featured image" class="w-full h-40 object-cover rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Current featured image</p>
                    </div>
                    @endif
                    <input type="file" name="featured_image" accept="image/*" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewFeaturedImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Upload new image to replace. Max 5MB.</p>
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
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Published</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Featured post</span>
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 space-y-3">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition flex items-center justify-center">
                        <i class="ri-save-line mr-2"></i> Update Post
                    </button>
                    
                    <form action="{{ route('admin.blog.posts.duplicate', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition flex items-center justify-center">
                            <i class="ri-file-copy-line mr-2"></i> Duplicate Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('select').select2({
            theme: 'classic',
            width: '100%'
        });
    });

    // Preview new featured image
    function previewFeaturedImage(event) {
        const preview = document.getElementById('featuredImagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="New featured image preview" class="w-full h-40 object-cover rounded-lg">
                        <button type="button" onclick="removeFeaturedImage()" class="remove-image">
                            <i class="ri-close-line"></i>
                        </button>
                        <p class="text-xs text-green-600 mt-1">New image to be uploaded</p>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    // Remove new featured image preview
    function removeFeaturedImage() {
        document.querySelector('input[name="featured_image"]').value = '';
        document.getElementById('featuredImagePreview').innerHTML = '';
    }

    // Remove current gallery image
    function removeCurrentImage(imagePath, button) {
        const container = button.parentElement;
        const hiddenInput = container.querySelector('input[type="hidden"]');
        
        if (hiddenInput.disabled) {
            hiddenInput.disabled = false;
            container.style.opacity = '0.4';
            button.innerHTML = '<i class="ri-undo-line"></i>';
            button.title = 'Undo remove';
        } else {
            hiddenInput.disabled = true;
            container.style.opacity = '1';
            button.innerHTML = '<i class="ri-close-line"></i>';
            button.title = 'Remove image';
        }
    }

    // Preview new gallery images
    function previewNewGalleryImages(event) {
        const preview = document.getElementById('newGalleryPreview');
        const files = event.target.files;
        
        let html = '';
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                html += `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="New gallery image ${i + 1}">
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

    // Initialize auto reading time based on current content
    updateReadingTime();
</script>
@endpush