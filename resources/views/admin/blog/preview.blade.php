@extends('layouts.admin')

@section('title', 'Preview - ' . $post->title)

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Preview Post</h1>
            <p class="text-gray-600 mt-1">Previewing: {{ $post->title }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.blog.posts.edit', $post) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="ri-edit-line mr-1"></i> Edit Post
            </a>
            <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="ri-arrow-left-line mr-1"></i> Back to Posts
            </a>
        </div>
    </div>

    <!-- Status Badges -->
    <div class="flex gap-2 mb-6">
        @if($post->is_published)
            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                <i class="ri-checkbox-circle-line mr-1"></i> Published
            </span>
        @else
            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                <i class="ri-draft-line mr-1"></i> Draft
            </span>
        @endif
        
        @if($post->is_featured)
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">
                <i class="ri-star-line mr-1"></i> Featured
            </span>
        @endif
        
        <span class="px-3 py-1 {{ $post->getPostTypeBadgeAttribute()[0] }} text-sm rounded-full">
            {{ $post->getPostTypeBadgeAttribute()[1] }}
        </span>
    </div>

    <!-- Post Content Preview -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="w-full h-96 overflow-hidden">
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <div class="p-8">
            <!-- Meta Information -->
            <div class="flex items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b">
                <div class="flex items-center gap-1">
                    <i class="ri-user-line"></i>
                    <span>{{ $post->author->name ?? 'Unknown' }}</span>
                </div>
                
                @if($post->category)
                <div class="flex items-center gap-1">
                    <i class="ri-folder-line"></i>
                    <span>{{ $post->category->name }}</span>
                </div>
                @endif
                
                <div class="flex items-center gap-1">
                    <i class="ri-time-line"></i>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
                
                @if($post->published_at)
                <div class="flex items-center gap-1">
                    <i class="ri-calendar-line"></i>
                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                @endif
                
                <div class="flex items-center gap-1">
                    <i class="ri-eye-line"></i>
                    <span>{{ number_format($post->views) }} views</span>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

            <!-- Tags -->
            @if($post->tags && count($post->tags) > 0)
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($post->tags as $tag)
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">#{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            <!-- Excerpt -->
            @if($post->excerpt)
            <div class="bg-gray-50 rounded-lg p-4 mb-6 border-l-4 border-blue-500">
                <p class="text-gray-700 italic">{{ $post->excerpt }}</p>
            </div>
            @endif

            <!-- Content -->
            <div class="prose max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Gallery Images -->
            @if($post->gallery_images && count($post->gallery_images) > 0)
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Gallery</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($post->gallery_images as $image)
                    <div class="rounded-lg overflow-hidden">
                        <img src="{{ route('file.show', ['path' => $image]) }}" alt="Gallery image" class="w-full h-48 object-cover hover:scale-105 transition-transform">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Video -->
            @if($post->video_url)
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Video</h3>
                <div class="bg-gray-100 rounded-lg p-4">
                    <a href="{{ $post->video_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                        <i class="ri-video-line"></i>
                        <span>{{ $post->video_url }}</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- SEO Preview -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="ri-search-line mr-2"></i> SEO Preview
        </h3>
        
        <div class="border rounded-lg p-4 bg-gray-50">
            <div class="text-blue-700 text-xl hover:underline cursor-pointer mb-1">
                {{ $post->meta_title ?: $post->title }}
            </div>
            <div class="text-green-700 text-sm mb-2">
                {{ request()->getSchemeAndHttpHost() }}/blog/{{ $post->slug }}
            </div>
            <div class="text-gray-600 text-sm">
                {{ $post->meta_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-1">Meta Title</h4>
                <p class="text-sm text-gray-600">{{ $post->meta_title ?: 'Using post title' }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-1">Meta Keywords</h4>
                <p class="text-sm text-gray-600">{{ $post->meta_keywords ?: 'Not set' }}</p>
            </div>
        </div>
    </div>

    <!-- Post Details -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Post Details</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-sm text-gray-600">Slug:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->slug }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Post Type:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->post_type_label }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Created:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Last Updated:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->updated_at->format('M d, Y H:i') }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Author:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->author->name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Category:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $post->category->name ?? 'None' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection