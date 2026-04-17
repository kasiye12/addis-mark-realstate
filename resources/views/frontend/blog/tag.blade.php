@extends('layouts.frontend')

@section('title', '#' . $tag . ' - Blog - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('content')
<section class="relative bg-gradient-to-br from-blue-600 to-blue-800 py-16 lg:py-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4">
            <i class="ri-price-tag-3-line mr-2"></i>Tag
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">#{{ $tag }}</h1>
        <p class="text-xl text-white/80">{{ $posts->total() }} article{{ $posts->total() != 1 ? 's' : '' }} with this tag</p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                        <article class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img src="{{ $post->featured_image_url }}" class="w-full h-48 object-cover">
                            </a>
                            <div class="p-6">
                                <h3 class="text-xl font-bold">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mt-2">{{ $post->published_at->format('M d, Y') }}</p>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <div class="mt-12">{{ $posts->links() }}</div>
                @else
                    <p class="text-gray-500 text-center py-16">No posts with this tag.</p>
                @endif
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="font-bold mb-4">Categories</h3>
                    @foreach($categories as $cat)
                    <a href="{{ route('blog.category', $cat->slug) }}" class="flex justify-between py-2 hover:text-blue-600">
                        <span>{{ $cat->name }}</span>
                        <span class="text-gray-500">{{ $cat->posts_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection