@extends('layouts.frontend')

@section('title', 'Blog & News - ' . setting('site_name', 'Addis Mark Real Estate'))

@push('styles')
<style>
    .blog-card {
        transition: all 0.3s ease;
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.15);
    }
    .blog-card:hover img {
        transform: scale(1.05);
    }
    .blog-card img {
        transition: transform 0.5s ease;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4">
            <i class="ri-article-line mr-2"></i>Insights & Updates
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
            Blog & News
        </h1>
        <p class="text-xl text-white/80 max-w-2xl mx-auto">
            Real estate tips, market updates, and investment advice
        </p>
    </div>
</section>

<!-- Category Tabs -->
<section class="bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center -mb-px">
            <a href="{{ route('blog.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ !request('type') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-article-line text-lg"></i>
                <span>All Posts</span>
            </a>
            <a href="{{ route('blog.index', ['type' => 'tip']) }}" 
               class="inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('type') == 'tip' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-lightbulb-line text-lg"></i>
                <span>Real Estate Tips</span>
            </a>
            <a href="{{ route('blog.index', ['type' => 'market_update']) }}" 
               class="inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('type') == 'market_update' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-line-chart-line text-lg"></i>
                <span>Market Updates</span>
            </a>
            <a href="{{ route('blog.index', ['type' => 'investment']) }}" 
               class="inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('type') == 'investment' ? 'border-amber-600 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-money-dollar-circle-line text-lg"></i>
                <span>Investment Advice</span>
            </a>
        </div>
    </div>
</section>

<!-- Blog Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                        <article class="blog-card bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block relative h-52 overflow-hidden">
                                <img src="{{ $post->featured_image_url }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $post->post_type_badge[0] }}">
                                        {{ $post->post_type_badge[1] }}
                                    </span>
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <i class="ri-user-line"></i> {{ $post->author->name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ri-calendar-line"></i> {{ $post->published_at->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ri-time-line"></i> {{ $post->reading_time }} min
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600 transition">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>
                                
                                <a href="{{ route('blog.show', $post->slug) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    Read More
                                    <i class="ri-arrow-right-line ml-1"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ri-article-line text-5xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No posts found</h3>
                        <p class="text-gray-500">Check back later for new content.</p>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-folder-line text-blue-600"></i> Categories
                    </h3>
                    <div class="space-y-1">
                        @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                            <span class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                                <span class="text-gray-700 group-hover:text-blue-600">{{ $category->name }}</span>
                            </span>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{{ $category->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Featured Posts -->
                @if($featuredPosts->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-star-line text-blue-600"></i> Featured Posts
                    </h3>
                    <div class="space-y-4">
                        @foreach($featuredPosts as $featured)
                        <a href="{{ route('blog.show', $featured->slug) }}" class="flex gap-4 group">
                            <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}" 
                                 class="w-20 h-20 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition line-clamp-2 text-sm">
                                    {{ $featured->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $featured->published_at->format('M d, Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Popular Tags -->
                @if(count($popularTags) > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-price-tag-3-line text-blue-600"></i> Popular Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" 
                           class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-blue-600 hover:text-white transition">
                            #{{ $tag }}
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