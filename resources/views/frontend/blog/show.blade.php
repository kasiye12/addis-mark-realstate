@extends('layouts.frontend')

@section('title', $post->meta_title ?: $post->title . ' - ' . setting('site_name', 'Addis Mark Real Estate'))
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?? $post->content), 160))
@section('meta_image', $post->featured_image_url)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Reset margins and ensure full width */
    body {
        overflow-x: hidden;
    }

    /* Hero Section - Full Screen */
    .post-hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: flex-end;
        padding-bottom: 80px;
        background-attachment: fixed;
    }
    
    .post-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.3) 100%);
        z-index: 1;
    }
    
    .post-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
    }
    
    /* Category Badge - Keep visible on hero */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 100px;
        color: white;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    /* Article Content - Full width and white background */
    .article-section {
        background: white;
        width: 100%;
    }
    
    .article-content {
        color: #374151;
        line-height: 1.9;
        font-size: 1.125rem;
        width: 100%;
    }
    
    /* Make content container full width on large screens */
    .content-full-width {
        width: 100%;
        max-width: 100%;
        padding-left: 2rem;
        padding-right: 2rem;
    }
    
    @media (min-width: 1280px) {
        .content-full-width {
            padding-left: 4rem;
            padding-right: 4rem;
        }
    }
    
    /* For the main content text, keep readable width */
    .article-content-wrapper {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .article-content h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
    }
    
    .article-content p {
        margin-bottom: 1.75rem;
    }
    
    .article-content p:first-of-type::first-letter {
        font-size: 3.5rem;
        font-weight: 700;
        float: left;
        line-height: 1;
        margin-right: 1rem;
        color: #2563eb;
    }
    
    .article-content ul {
        margin-bottom: 1.75rem;
        padding-left: 1.5rem;
    }
    
    .article-content li {
        margin-bottom: 0.75rem;
        position: relative;
        padding-left: 0.5rem;
    }
    
    .article-content li::marker {
        color: #2563eb;
    }
    
    .article-content blockquote {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border-left: 4px solid #2563eb;
        padding: 1.5rem 2rem;
        margin: 2.5rem 0;
        border-radius: 0 16px 16px 0;
        font-size: 1.25rem;
        color: #1e293b;
    }
    
    /* Highlight Box */
    .highlight-box {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin: 2.5rem 0;
    }
    
    /* Stats Card */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        text-align: center;
    }
    
    /* Author Card */
    .author-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
        border: 1px solid #f0f0f0;
    }
    
    /* Share Buttons - Redesigned for white background */
    .share-buttons-wrapper {
        background: white;
        border-radius: 60px;
        padding: 8px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #eef2f6;
        display: inline-flex;
        gap: 12px;
    }
    
    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 1.25rem;
        background: #f8fafc;
        color: #64748b;
    }
    
    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .share-btn.facebook:hover { background: #1877f2; color: white; }
    .share-btn.twitter:hover { background: #000000; color: white; }
    .share-btn.linkedin:hover { background: #0a66c2; color: white; }
    .share-btn.whatsapp:hover { background: #25d366; color: white; }
    .share-btn.copy:hover { background: #2563eb; color: white; }
    
    /* Related Card */
    .related-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .related-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
    }
    
    /* Progress Bar */
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
        z-index: 100;
        transition: width 0.1s ease;
    }
    
    /* Sidebar sticky */
    .sidebar-sticky {
        position: sticky;
        top: 100px;
    }
    
    /* Full width background for article */
    .article-full-bg {
        background: white;
        width: 100%;
    }
    
    /* Two column layout with proper spacing */
    .two-column-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 3rem;
    }
    
    @media (max-width: 1024px) {
        .two-column-layout {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .sidebar-sticky {
            position: static;
        }
    }
</style>
@endpush

@section('content')

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<!-- Hero Section - Full Screen -->
<section class="post-hero" style="background-image: url('{{ $post->featured_image_url }}'); background-size: cover; background-position: center;">
    <div class="post-hero-overlay"></div>
    
    <div class="post-hero-content container mx-auto px-4 md:px-8 lg:px-12">
        <div class="max-w-5xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm mb-6 text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i class="ri-arrow-right-s-line mx-2"></i>
                <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
                @if($post->category)
                <i class="ri-arrow-right-s-line mx-2"></i>
                <a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-white transition">{{ $post->category->name }}</a>
                @endif
            </nav>
            
            <!-- Category Badge -->
            <div class="flex flex-wrap items-center gap-3 mb-5">
                <span class="category-badge">
                    <i class="ri-{{ $post->post_type == 'tip' ? 'lightbulb-flash-line' : ($post->post_type == 'market_update' ? 'line-chart-line' : ($post->post_type == 'investment' ? 'money-dollar-circle-line' : 'article-line')) }}"></i>
                    {{ $post->post_type_badge[1] }}
                </span>
                @if($post->category)
                <span class="category-badge" style="background: {{ $post->category->color }}30; border-color: {{ $post->category->color }}50;">
                    <span class="w-2 h-2 rounded-full" style="background: {{ $post->category->color }}"></span>
                    {{ $post->category->name }}
                </span>
                @endif
                @if($post->is_featured)
                <span class="category-badge" style="background: #f59e0b30; border-color: #f59e0b50;">
                    <i class="ri-star-fill text-amber-400"></i> Featured
                </span>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-6 leading-tight drop-shadow-lg">
                {{ $post->title }}
            </h1>
            
            <!-- Excerpt -->
            @if($post->excerpt)
            <p class="text-xl md:text-2xl text-white/90 mb-8 leading-relaxed max-w-3xl drop-shadow">
                {{ $post->excerpt }}
            </p>
            @endif
            
            <!-- Author & Meta -->
            <div class="flex flex-wrap items-center gap-6 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg border-2 border-white/30">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $post->author->name }}</p>
                        <p class="text-sm text-white/70">{{ $post->author->role_badge[1] ?? 'Author' }}</p>
                    </div>
                </div>
                <div class="w-px h-8 bg-white/30"></div>
                <div class="flex items-center gap-5">
                    <span class="flex items-center gap-2">
                        <i class="ri-calendar-line"></i> {{ $post->published_at->format('M d, Y') }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="ri-time-line"></i> {{ $post->reading_time }} min read
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="ri-eye-line"></i> {{ number_format($post->views) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Article Content - Full Width with White Background -->
<article class="article-full-bg py-12 lg:py-16">
    <div class="content-full-width mx-auto">
        <div class="two-column-layout">
            <!-- Main Content Column -->
            <div class="article-content-wrapper">
                <div class="article-content">
                    {!! $post->content !!}
                </div>
                
                <!-- Highlight Box -->
                <div class="highlight-box" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-lightbulb-flash-line text-2xl text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold mb-2">Key Takeaway</h4>
                            <p class="text-white/90">Investing in Addis Ababa's modern apartments offers strong returns due to rapid urbanization and high rental demand. Choose locations near business districts and amenities for maximum appreciation.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tags -->
                @if($post->tags && count($post->tags) > 0)
                <div class="mt-10 pt-8 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="ri-hashtag text-blue-600"></i>
                        Related Topics
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" 
                           class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-full hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow">
                            {{ $tag }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Share Section - Redesigned for white background -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="ri-share-forward-fill text-blue-600"></i>
                        Share This Article
                    </p>
                    <div class="share-buttons-wrapper">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" 
                           class="share-btn facebook">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" target="_blank" 
                           class="share-btn twitter">
                            <i class="ri-twitter-x-fill"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" target="_blank" 
                           class="share-btn linkedin">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" 
                           class="share-btn whatsapp">
                            <i class="ri-whatsapp-line"></i>
                        </a>
                        <button onclick="copyLink()" class="share-btn copy">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Author Card -->
                <div class="mt-10 author-card" data-aos="fade-up">
                    <div class="flex flex-wrap items-start gap-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-xl">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h4 class="text-xl font-bold text-gray-900">{{ $post->author->name }}</h4>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $post->author->role_badge[0] }}">
                                    {{ $post->author->role_badge[1] }}
                                </span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $post->author->bio ?? 'Real estate specialist with extensive knowledge of the Ethiopian property market. Passionate about helping clients find their perfect investment.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Column -->
            <div class="sidebar-sticky space-y-6">
                <!-- Table of Contents -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-list-check text-blue-600 text-xl"></i>
                        In This Article
                    </h4>
                    <div id="tableOfContents" class="space-y-1 max-h-[400px] overflow-y-auto">
                        <!-- JavaScript will populate this -->
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="stat-card">
                        <i class="ri-eye-line text-2xl text-blue-600 mb-2"></i>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($post->views) }}</p>
                        <p class="text-xs text-gray-500">Total Views</p>
                    </div>
                    <div class="stat-card">
                        <i class="ri-bookmark-line text-2xl text-green-600 mb-2"></i>
                        <p class="text-2xl font-bold text-gray-900">{{ $post->reading_time }}</p>
                        <p class="text-xs text-gray-500">Min Read</p>
                    </div>
                </div>
                
                <!-- Newsletter -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white">
    <i class="ri-mail-send-line text-3xl mb-3 opacity-80"></i>
    <h4 class="text-lg font-bold mb-2">Get Property Insights</h4>
    <p class="text-blue-100 text-sm mb-4">Subscribe for expert real estate tips and market updates.</p>
    
    @if(session('success'))
        <div class="bg-green-500 text-white text-sm p-2 rounded-lg mb-3">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-500 text-white text-sm p-2 rounded-lg mb-3">
            {{ session('error') }}
        </div>
    @endif
    
    <form action="{{ route('newsletter.subscribe') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Your email" required
               class="w-full px-4 py-2.5 rounded-xl text-gray-900 text-sm mb-3 @error('email') border-2 border-red-500 @enderror">
        @error('email')
            <p class="text-red-200 text-xs mb-2">{{ $message }}</p>
        @enderror
        <button type="submit" class="w-full py-2.5 bg-white text-blue-700 font-semibold rounded-xl hover:bg-gray-100 transition text-sm">
            Subscribe Now
        </button>
    </form>
</div>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if(isset($relatedPosts) && $relatedPosts->count() > 0)
<section class="py-16 lg:py-20 bg-gray-50">
    <div class="container mx-auto px-4 md:px-8 lg:px-12">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Keep Reading</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Related Articles</h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="related-card group">
                <div class="relative h-52 overflow-hidden">
                    <img src="{{ $related->featured_image_url }}" 
                         alt="{{ $related->title }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm text-white">
                            {{ $related->post_type_badge[1] }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <span class="text-xs text-gray-500 flex items-center gap-1 mb-2">
                        <i class="ri-calendar-line"></i> {{ $related->published_at->format('M d, Y') }}
                    </span>
                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition text-lg line-clamp-2 mb-2">
                        {{ $related->title }}
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($related->excerpt ?? $related->content), 80) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-slate-900 to-blue-900">
    <div class="container mx-auto px-4 text-center">
        <div data-aos="zoom-in">
            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-4">
                Ready to Find Your Dream Property?
            </h2>
            <p class="text-white/80 text-xl mb-8 max-w-2xl mx-auto">
                Browse our exclusive collection of premium properties in Ethiopia's most desirable locations.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('properties.index') }}" class="px-8 py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold rounded-xl transition shadow-xl">
                    <i class="ri-building-line mr-2"></i> Browse Properties
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 border-2 border-white/30 hover:border-white text-white font-semibold rounded-xl hover:bg-white/10 transition">
                    <i class="ri-message-2-line mr-2"></i> Contact Agent
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 800, once: true });
        
        // Reading Progress Bar
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progressBar = document.getElementById('readingProgress');
            if (progressBar) {
                progressBar.style.width = scrolled + '%';
            }
        });
        
        // Generate Table of Contents
        const content = document.querySelector('.article-content');
        const toc = document.getElementById('tableOfContents');
        
        if (content && toc) {
            const headings = content.querySelectorAll('h2, h3');
            if (headings.length > 0) {
                headings.forEach((heading, index) => {
                    const id = 'section-' + index;
                    heading.id = id;
                    
                    const link = document.createElement('a');
                    link.href = '#' + id;
                    link.className = 'block py-2 px-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition text-sm ' + 
                                    (heading.tagName === 'H3' ? 'pl-6' : 'font-medium');
                    link.innerHTML = '<i class="ri-arrow-right-s-line text-blue-400 mr-1"></i>' + heading.textContent;
                    toc.appendChild(link);
                });
            } else {
                toc.innerHTML = '<p class="text-gray-400 text-sm p-2">No sections found</p>';
            }
        }
        
        // Smooth scroll for TOC links
        document.querySelectorAll('#tableOfContents a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
    
    function copyLink() {
        navigator.clipboard.writeText(window.location.href);
        // Optional: Show a toast notification instead of alert
        alert('Link copied to clipboard!');
    }
</script>
@endpush