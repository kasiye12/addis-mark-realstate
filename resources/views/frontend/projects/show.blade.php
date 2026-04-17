@extends('layouts.frontend')

@section('title', $project->title . ' - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('meta_description', Str::limit($project->short_description ?? $project->description, 160))
@section('meta_image', $project->featured_image_url)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    /* Gallery */
    .gallery-main {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
    }
    .gallery-main .swiper-slide {
        height: 500px;
    }
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-thumbs {
        height: 500px;
        border-radius: 16px;
        overflow: hidden;
    }
    .gallery-thumbs .swiper-slide {
        height: 160px;
        opacity: 0.5;
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
    }
    .gallery-thumbs .swiper-slide-thumb-active {
        opacity: 1;
        box-shadow: 0 0 0 3px #2563eb, 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        backdrop-filter: blur(8px);
    }
    
    /* Feature Card */
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1);
        border-color: #2563eb;
    }
    
    /* Amenity Item */
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #f8fafc;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .amenity-item:hover {
        background: #2563eb;
        border-color: #2563eb;
    }
    .amenity-item:hover i,
    .amenity-item:hover span {
        color: white !important;
    }
    
    /* Spec Item */
    .spec-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .spec-item:last-child {
        border-bottom: none;
    }
    
    /* Inquiry Card */
    .inquiry-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        position: relative;
        overflow: hidden;
    }
    .inquiry-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.5; }
    }
    
    /* Related Project Card */
    .related-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.12);
    }
    .related-card img {
        transition: transform 0.5s ease;
    }
    .related-card:hover img {
        transform: scale(1.08);
    }
    
    /* Floating Action Bar */
    .floating-bar {
        backdrop-filter: blur(12px);
        background: rgba(255,255,255,0.95);
        box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
    }
</style>
@endpush

@section('content')

<!-- Floating Action Bar (Mobile) -->
<div class="fixed bottom-0 left-0 right-0 floating-bar lg:hidden z-40 border-t border-gray-200 px-4 py-3">
    <div class="flex items-center justify-between">
        <div>
            @if($project->starting_price)
                <span class="text-xl font-bold text-blue-600">ETB {{ number_format($project->starting_price) }}</span>
                <span class="text-sm text-gray-500 ml-1">Starting</span>
            @endif
        </div>
        <a href="#inquiry" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl shadow-md">
            <i class="ri-message-2-line mr-1"></i> Inquire Now
        </a>
    </div>
</div>

<!-- Breadcrumb -->
<div class="bg-white border-b border-gray-100">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-blue-600 transition">
                <i class="ri-home-4-line"></i>
            </a>
            <i class="ri-arrow-right-s-line text-gray-300 mx-2"></i>
            <a href="{{ route('projects.index') }}" class="text-gray-500 hover:text-blue-600 transition">
                Projects
            </a>
            <i class="ri-arrow-right-s-line text-gray-300 mx-2"></i>
            <span class="text-gray-700 font-medium truncate">{{ $project->title }}</span>
        </nav>
    </div>
</div>

<!-- Hero Section -->
<section class="relative bg-white">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        <!-- Status & Meta -->
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <span class="status-badge {{ $project->status == 'ongoing' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($project->status == 'completed' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $project->status == 'ongoing' ? 'bg-blue-500' : ($project->status == 'completed' ? 'bg-green-500' : 'bg-amber-500') }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $project->status == 'ongoing' ? 'bg-blue-600' : ($project->status == 'completed' ? 'bg-green-600' : 'bg-amber-600') }}"></span>
                </span>
                {{ $project->status_badge[1] }}
            </span>
            
            @if($project->is_featured)
            <span class="status-badge bg-amber-50 text-amber-700 border border-amber-200">
                <i class="ri-star-fill text-amber-500"></i>
                Featured Project
            </span>
            @endif
            
            <span class="status-badge bg-gray-50 text-gray-600 border border-gray-200">
                <i class="ri-eye-line"></i>
                {{ number_format($project->views) }} Views
            </span>
            
            @if($project->category)
            <span class="status-badge bg-purple-50 text-purple-700 border border-purple-200">
                <i class="ri-price-tag-3-line"></i>
                {{ $project->category->name }}
            </span>
            @endif
        </div>
        
        <!-- Title -->
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
            {{ $project->title }}
        </h1>
        
        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-map-pin-line text-blue-600"></i>
                </div>
                <span class="font-medium">{{ $project->location }}</span>
            </div>
            @if($project->developer)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-purple-600"></i>
                </div>
                <span class="font-medium">{{ $project->developer }}</span>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Gallery Section -->
@if($project->gallery && count($project->gallery) > 0)
<section class="pb-8">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-4 gap-5">
            <div class="lg:col-span-3">
                <div class="swiper gallery-main">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ $project->featured_image_url }}" alt="{{ $project->title }}">
                        </div>
                        @foreach($project->gallery as $image)
                            @if(is_string($image))
                            <div class="swiper-slide">
                                <img src="{{ route('file.show', ['path' => $image]) }}" alt="{{ $project->title }}">
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="swiper-button-next !text-gray-700 !bg-white/90 !w-12 !h-12 !rounded-full after:!text-lg shadow-lg"></div>
                    <div class="swiper-button-prev !text-gray-700 !bg-white/90 !w-12 !h-12 !rounded-full after:!text-lg shadow-lg"></div>
                    <div class="swiper-pagination !bottom-4"></div>
                </div>
            </div>
            <div class="lg:col-span-1 hidden lg:block">
                <div class="swiper gallery-thumbs">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ $project->featured_image_url }}" alt="Thumbnail">
                        </div>
                        @foreach($project->gallery as $image)
                            @if(is_string($image))
                            <div class="swiper-slide">
                                <img src="{{ route('file.show', ['path' => $image]) }}" alt="Thumbnail">
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-white rounded-full text-sm font-medium shadow-sm border">
                        <i class="ri-camera-line text-blue-600"></i>
                        <span>{{ count($project->gallery) + 1 }} Photos</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
@elseif($project->featured_image)
<section class="pb-8">
    <div class="container mx-auto px-4">
        <img src="{{ $project->featured_image_url }}" alt="{{ $project->title }}" class="w-full max-h-[600px] object-cover rounded-3xl shadow-xl">
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-10">
            <!-- Left Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Price & Quick Actions -->
                @if($project->starting_price)
                <div class="flex flex-wrap items-center justify-between gap-4 p-6 bg-gradient-to-r from-blue-50 to-white rounded-2xl border border-blue-100">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Starting Price</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold text-blue-600">ETB {{ number_format($project->starting_price) }}</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="shareProject()" class="flex items-center gap-2 px-5 py-3 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition">
                            <i class="ri-share-forward-line text-xl"></i>
                            <span class="font-medium">Share</span>
                        </button>
                        <a href="#inquiry" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            <i class="ri-message-2-line text-xl"></i>
                            <span class="font-semibold">Inquire</span>
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Description -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-7 bg-blue-600 rounded-full"></span>
                        About This Project
                    </h2>
                    <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                </div>
                
                <!-- Specifications -->
                @php
                    $specs = $project->specifications;
                    if (is_string($specs)) {
                        $specs = json_decode($specs, true);
                    }
                @endphp
                @if(!empty($specs) && is_array($specs))
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-7 bg-blue-600 rounded-full"></span>
                        Specifications
                    </h2>
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        @foreach($specs as $key => $value)
                            @if(is_string($value) || is_numeric($value))
                            <div class="spec-item">
                                <span class="text-gray-600 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $value }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Amenities -->
                @php
                    $amenities = $project->amenities;
                    if (is_string($amenities)) {
                        $amenities = json_decode($amenities, true);
                    }
                    if (!is_array($amenities)) {
                        $amenities = array_filter(explode(',', $amenities ?? ''));
                    }
                @endphp
                @if(!empty($amenities))
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-7 bg-blue-600 rounded-full"></span>
                        Amenities
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($amenities as $amenity)
                            @php $amenity = is_string($amenity) ? trim($amenity) : ''; @endphp
                            @if(!empty($amenity))
                            <div class="amenity-item">
                                <i class="ri-check-line text-green-600 text-xl"></i>
                                <span class="font-medium text-gray-700">{{ $amenity }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6" id="inquiry">
                <!-- Project Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <i class="ri-information-line text-blue-600"></i>
                        Project Details
                    </h3>
                    
                    <div class="space-y-1">
                        @if($project->starting_price)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Starting Price</span>
                            <span class="font-bold text-blue-600">ETB {{ number_format($project->starting_price) }}</span>
                        </div>
                        @endif
                        
                        @if($project->start_date)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Start Date</span>
                            <span class="font-medium">{{ $project->start_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                        
                        @if($project->completion_date)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Completion</span>
                            <span class="font-medium">{{ $project->completion_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium text-{{ $project->status == 'ongoing' ? 'blue' : ($project->status == 'completed' ? 'green' : 'amber') }}-600">
                                {{ $project->status_badge[1] }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between py-3">
                            <span class="text-gray-500">Listed</span>
                            <span class="font-medium">{{ $project->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Inquiry Form -->
                <div class="inquiry-card rounded-2xl p-6 text-white sticky top-24">
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold mb-2">Request Information</h3>
                        <p class="text-blue-100 text-sm mb-5">Fill out the form and we'll get back to you shortly.</p>
                        
                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subject" value="Inquiry about {{ $project->title }}">
                            
                            <div class="space-y-3">
                                <input type="text" name="name" placeholder="Your Full Name" required
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder:text-white/60 focus:outline-none focus:border-white/40">
                                
                                <input type="email" name="email" placeholder="Email Address" required
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder:text-white/60 focus:outline-none focus:border-white/40">
                                
                                <input type="tel" name="phone" placeholder="Phone Number" required
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder:text-white/60 focus:outline-none focus:border-white/40">
                                
                                <textarea name="message" rows="3" placeholder="I'm interested in {{ $project->title }}. Please contact me with more information."
                                          class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder:text-white/60 focus:outline-none focus:border-white/40"></textarea>
                                
                                <button type="submit" class="w-full py-3 bg-white text-gray-900 font-semibold rounded-xl hover:bg-gray-100 transition transform hover:-translate-y-0.5 shadow-lg">
                                    <i class="ri-send-plane-fill mr-2"></i> Send Inquiry
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-white/60 text-xs text-center mt-4">
                            <i class="ri-lock-line mr-1"></i> Your information is secure
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Projects -->
@if(isset($relatedProjects) && $relatedProjects->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">You May Also Like</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Similar Projects</h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedProjects as $related)
            <a href="{{ route('projects.show', $related->slug) }}" class="related-card group">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $related->status_badge[0] }}">
                            {{ $related->status_badge[1] }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-sm text-gray-500 mb-1 flex items-center gap-1">
                        <i class="ri-map-pin-line text-blue-600 text-xs"></i>
                        {{ $related->location }}
                    </p>
                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2">
                        {{ $related->title }}
                    </h3>
                    @if($related->starting_price)
                    <p class="text-blue-600 font-semibold mt-3">ETB {{ number_format($related->starting_price) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Invest in Your Future?</h2>
        <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
            Schedule a consultation with our project specialists today.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-xl hover:bg-gray-100 transition shadow-lg">
                Contact Us
            </a>
            <a href="{{ route('projects.index') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-xl hover:bg-white/10 transition">
                View All Projects
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 800, once: true });
        
        // Gallery
        const galleryThumbs = new Swiper('.gallery-thumbs', {
            slidesPerView: 3,
            direction: 'vertical',
            spaceBetween: 10,
            watchSlidesProgress: true
        });
        
        new Swiper('.gallery-main', {
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
            thumbs: { swiper: galleryThumbs }
        });
    });
    
    function shareProject() {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({ title: '{{ $project->title }}', url });
        } else {
            navigator.clipboard.writeText(url);
            alert('Link copied to clipboard!');
        }
    }
</script>
@endpush