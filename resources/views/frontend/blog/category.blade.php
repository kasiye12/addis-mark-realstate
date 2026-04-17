@extends('layouts.frontend')

@section('title', $category->name . ' - Blog - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 to-blue-800 py-16 lg:py-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium mb-4" 
              style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
            <span class="w-2 h-2 rounded-full" style="background-color: {{ $category->color }}"></span>
            {{ $category->name }}
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $category->name }}</h1>
        <p class="text-xl text-white/80">Browse all articles in this category</p>
    </div>
</section>

<!-- Blog Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                        <article class="blog-card bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block relative h-52 overflow-hidden">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $post->post_type_badge[0] }}">
                                        {{ $post->post_type_badge[1] }}
                                    </span>
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                                    <span><i class="ri-user-line"></i> {{ $post->author->name }}</span>
                                    <span><i class="ri-calendar-line"></i> {{ $post->published_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    Read More <i class="ri-arrow-right-line ml-1"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <div class="mt-12">{{ $posts->links() }}</div>
                @else
                    <div class="text-center py-16">
                        <i class="ri-article-line text-5xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No posts in this category yet.</p>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                    <div class="space-y-1">
                        @foreach($categories as $cat)
                        <a href="{{ route('blog.category', $cat->slug) }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition {{ $cat->id == $category->id ? 'bg-blue-50' : '' }}">
                            <span class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                <span class="{{ $cat->id == $category->id ? 'text-blue-600 font-medium' : 'text-gray-700' }}">{{ $cat->name }}</span>
                            </span>
                            <span class="text-sm text-gray-500">{{ $cat->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                
                @if($featuredPosts->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Featured Posts</h3>
                    <div class="space-y-4">
                        @foreach($featuredPosts as $featured)
                        <a href="{{ route('blog.show', $featured->slug) }}" class="flex gap-4 group">
                            <img src="{{ $featured->featured_image_url }}" class="w-20 h-20 rounded-lg object-cover">
                            <div>
                                <h4 class="font-medium group-hover:text-blue-600 line-clamp-2 text-sm">{{ $featured->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $featured->published_at->format('M d, Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection