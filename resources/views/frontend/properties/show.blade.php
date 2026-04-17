@extends('layouts.frontend')

@php
    $siteName = \App\Models\Setting::get('site_name', 'Addis Mark Real Estate');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $propertyImages = $property->images;
    $imageCount = $propertyImages->count();
@endphp

@section('title', $property->title . ' - ' . $siteName)
@section('meta_description', Str::limit(strip_tags($property->description), 160))
@section('meta_keywords', $property->category->name . ', ' . $property->location->area_name . ', real estate, property, Ethiopia, luxury')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    
    .gallery-main {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
    }
    .gallery-main .swiper-slide {
        height: 480px;
        cursor: pointer;
    }
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-thumbs {
        height: 480px;
        border-radius: 12px;
        overflow: hidden;
    }
    .gallery-thumbs .swiper-slide {
        height: 152px;
        opacity: 0.6;
        transition: all 0.2s ease;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
    }
    .gallery-thumbs .swiper-slide-thumb-active {
        opacity: 1;
        box-shadow: 0 0 0 2px #2563eb;
    }
    .gallery-thumbs img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .feature-card {
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .feature-card:hover {
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }
    .agent-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .amenity-icon {
        width: 44px;
        height: 44px;
        background: #eff6ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .amenity-item:hover .amenity-icon {
        background: #2563eb;
    }
    .amenity-item:hover .amenity-icon i {
        color: white !important;
    }
    .floating-bar {
        backdrop-filter: blur(8px);
        background: rgba(255,255,255,0.95);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    .favorite-btn {
        transition: all 0.2s ease;
    }
    .favorite-btn:hover {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
</style>
@endpush

@section('content')
<!-- Floating Action Bar (Mobile) -->
<div class="fixed bottom-0 left-0 right-0 floating-bar lg:hidden z-40 border-t border-gray-200 px-4 py-3">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-xl font-bold text-blue-600">ETB {{ number_format($property->price) }}</span>
            @if($property->price_type === 'rent')
                <span class="text-sm text-gray-500">/ month</span>
            @endif
        </div>
        <button onclick="openInquiryModal()" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg">
            <i class="ri-message-2-line mr-1"></i> Contact
        </button>
    </div>
</div>

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-3">
        <nav class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition">Home</a>
            <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
            <a href="{{ route('properties.index') }}" class="text-gray-500 hover:text-blue-600 transition">Properties</a>
            <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
            <a href="{{ route('properties.category', $property->category->slug) }}" class="text-gray-500 hover:text-blue-600 transition">{{ $property->category->name }}</a>
            <i class="ri-arrow-right-s-line text-gray-400 mx-2"></i>
            <span class="text-gray-700 font-medium truncate">{{ $property->title }}</span>
        </nav>
    </div>
</div>

<!-- Property Header -->
<div class="bg-white border-b border-gray-200">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <span class="px-3 py-1.5 text-xs font-semibold rounded-full 
                {{ $property->status === 'available' ? 'bg-green-100 text-green-700' : 
                   ($property->status === 'sold' ? 'bg-red-100 text-red-700' : 
                   ($property->status === 'rented' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                {{ ucfirst($property->status) }}
            </span>
            
            @if($property->is_featured)
            <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                <i class="ri-star-fill mr-1"></i> Featured
            </span>
            @endif
            
            <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                <i class="ri-eye-line mr-1"></i> {{ number_format($property->views) }} Views
            </span>
            
            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                {{ ucfirst($property->property_type) }}
            </span>
        </div>
        
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $property->title }}</h1>
        
        <div class="flex flex-wrap items-center gap-5 text-gray-600 text-sm">
            <span class="flex items-center gap-1">
                <i class="ri-map-pin-line text-blue-600"></i>
                {{ $property->location->area_name ?? '' }}, {{ $property->location->city ?? 'Addis Ababa' }}
            </span>
            <span class="flex items-center gap-1">
                <i class="ri-calendar-line text-blue-600"></i>
                Listed {{ $property->created_at->diffForHumans() }}
            </span>
            <span class="flex items-center gap-1">
                <i class="ri-hashtag text-blue-600"></i>
                ID: {{ str_pad($property->id, 6, '0', STR_PAD_LEFT) }}
            </span>
        </div>
    </div>
</div>

<!-- Gallery Section -->
<div class="container mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-4 gap-4">
        <!-- Main Gallery -->
        <div class="lg:col-span-3">
            @if($imageCount > 0)
                <div class="swiper gallery-main">
                    <div class="swiper-wrapper">
                        @foreach($propertyImages as $image)
                            <div class="swiper-slide" onclick="openFancybox({{ $loop->index }})">
                                <img src="{{ route('file.show', ['path' => $image->image_path]) }}" 
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy"
                                     onerror="this.src='https://via.placeholder.com/800x600?text=Property+Image'">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next !text-gray-700 !bg-white/80 !w-10 !h-10 !rounded-full after:!text-lg shadow-md"></div>
                    <div class="swiper-button-prev !text-gray-700 !bg-white/80 !w-10 !h-10 !rounded-full after:!text-lg shadow-md"></div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <div class="gallery-main bg-gray-100 flex items-center justify-center" style="height: 480px;">
                    <div class="text-center">
                        <i class="ri-image-line text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No images available</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Thumbnails -->
        <div class="lg:col-span-1 hidden lg:block">
            @if($imageCount > 0)
                <div class="swiper gallery-thumbs">
                    <div class="swiper-wrapper">
                        @foreach($propertyImages->take(3) as $index => $image)
                            <div class="swiper-slide">
                                <img src="{{ route('file.show', ['path' => $image->image_path]) }}" 
                                     alt="Thumbnail" 
                                     loading="lazy"
                                     onerror="this.src='https://via.placeholder.com/400x160?text=Thumbnail'">
                                @if($index === 0 && $imageCount > 3)
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                        <span class="text-white font-semibold">+{{ $imageCount - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @for($i = $imageCount; $i < 3; $i++)
                            <div class="swiper-slide bg-gray-100"></div>
                        @endfor
                    </div>
                </div>
            @else
                <div class="gallery-thumbs bg-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <i class="ri-image-line text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-400 text-sm">No thumbnails</p>
                    </div>
                </div>
            @endif
            
            <div class="mt-3 text-center">
                <span class="inline-flex items-center gap-1 px-4 py-2 bg-white rounded-full text-sm shadow-sm border">
                    <i class="ri-camera-line text-blue-600"></i>
                    <span>{{ $imageCount }} Photo{{ $imageCount != 1 ? 's' : '' }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Content Section -->
<section class="py-8 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Price & Actions -->
                <div class="flex flex-wrap items-center justify-between gap-4 p-5 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Price</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-blue-600">
                                ETB {{ number_format($property->price) }}
                            </span>
                            @if($property->price_type === 'rent')
                                <span class="text-gray-500">/ month</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="toggleFavorite({{ $property->id }})" 
                                class="favorite-btn flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            <i class="ri-heart-line"></i>
                            <span class="text-sm font-medium">Save</span>
                        </button>
                        <button onclick="shareProperty()" 
                                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            <i class="ri-share-line"></i>
                            <span class="text-sm font-medium">Share</span>
                        </button>
                        <a href="{{ route('properties.download-brochure', $property) }}" 
                           class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="ri-file-pdf-line"></i>
                            <span class="text-sm font-medium">Brochure</span>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-4 gap-3">
                    @if($property->bedrooms)
                    <div class="feature-card p-4 rounded-xl text-center">
                        <i class="ri-hotel-bed-line text-2xl text-blue-600 mb-2"></i>
                        <div class="text-xl font-bold text-gray-900">{{ $property->bedrooms }}</div>
                        <div class="text-xs text-gray-500">Bedrooms</div>
                    </div>
                    @endif
                    
                    @if($property->bathrooms)
                    <div class="feature-card p-4 rounded-xl text-center">
                        <i class="ri-drop-line text-2xl text-blue-600 mb-2"></i>
                        <div class="text-xl font-bold text-gray-900">{{ $property->bathrooms }}</div>
                        <div class="text-xs text-gray-500">Bathrooms</div>
                    </div>
                    @endif
                    
                    @if($property->area_sqm)
                    <div class="feature-card p-4 rounded-xl text-center">
                        <i class="ri-ruler-line text-2xl text-blue-600 mb-2"></i>
                        <div class="text-xl font-bold text-gray-900">{{ number_format($property->area_sqm) }}</div>
                        <div class="text-xs text-gray-500">Area (m²)</div>
                    </div>
                    @endif
                    
                    @if($property->year_built)
                    <div class="feature-card p-4 rounded-xl text-center">
                        <i class="ri-calendar-line text-2xl text-blue-600 mb-2"></i>
                        <div class="text-xl font-bold text-gray-900">{{ $property->year_built }}</div>
                        <div class="text-xs text-gray-500">Year Built</div>
                    </div>
                    @endif
                </div>
                
                <!-- Description -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                        About This Property
                    </h2>
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>
                
                <!-- Features & Amenities -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                        Features & Amenities
                    </h2>
                    
                    @php
                        $amenities = [
                            'parking' => ['icon' => 'ri-parking-box-line', 'label' => 'Parking'],
                            'furnished' => ['icon' => 'ri-sofa-line', 'label' => 'Furnished'],
                            'security' => ['icon' => 'ri-shield-check-line', 'label' => '24/7 Security'],
                            'elevator' => ['icon' => 'ri-arrow-up-down-line', 'label' => 'Elevator'],
                            'garden' => ['icon' => 'ri-plant-line', 'label' => 'Garden'],
                            'pool' => ['icon' => 'ri-water-flash-line', 'label' => 'Swimming Pool'],
                            'air_conditioning' => ['icon' => 'ri-windy-line', 'label' => 'Air Conditioning'],
                        ];
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($amenities as $key => $amenity)
                            @if($property->$key)
                                <div class="amenity-item flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="amenity-icon">
                                        <i class="{{ $amenity['icon'] }} text-xl text-blue-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $amenity['label'] }}</span>
                                    <i class="ri-check-line text-green-500 ml-auto"></i>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <!-- Location Map -->
                @if($property->location->latitude && $property->location->longitude)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                        Location
                    </h2>
                    
                    <div class="rounded-xl overflow-hidden border border-gray-200 h-64">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            src="https://maps.google.com/maps?q={{ $property->location->latitude }},{{ $property->location->longitude }}&z=15&output=embed"
                            class="w-full h-full">
                        </iframe>
                    </div>
                </div>
                @endif
                
                <!-- Video Tour -->
                @if($property->video_url)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                        Video Tour
                    </h2>
                    
                    <div class="rounded-2xl overflow-hidden shadow-xl">
                        @php
                            $youtubeId = '';
                            if (str_contains($property->video_url, 'youtube.com') || str_contains($property->video_url, 'youtu.be')) {
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $property->video_url, $matches);
                                $youtubeId = $matches[1] ?? '';
                            }
                        @endphp
                        
                        @if($youtubeId)
                            <div class="relative pb-[56.25%] h-0">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                    class="absolute top-0 left-0 w-full h-full"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <video controls class="w-full rounded-2xl" poster="{{ asset('images/video-poster.jpg') }}">
                                <source src="{{ $property->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Agent Contact Card -->
                <div class="agent-card rounded-2xl p-6 text-white sticky top-20">
                    <h3 class="text-xl font-bold mb-5">Contact Agent</h3>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($property->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ $property->user->name ?? 'Addis Mark Agent' }}</p>
                            <p class="text-blue-200 text-sm">Real Estate Specialist</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <a href="tel:{{ $property->user->phone ?? '+251911234567' }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 rounded-xl hover:bg-white/20 transition">
                            <i class="ri-phone-line text-xl"></i>
                            <span>{{ $property->user->phone ?? '+251 91 123 4567' }}</span>
                        </a>
                        
                        <a href="mailto:{{ $property->user->email ?? 'agent@addismark.com' }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 rounded-xl hover:bg-white/20 transition">
                            <i class="ri-mail-line text-xl"></i>
                            <span class="text-sm truncate">{{ $property->user->email ?? 'agent@addismark.com' }}</span>
                        </a>
                        
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->user->phone ?? '251911234567') }}" 
                           target="_blank"
                           class="flex items-center gap-3 p-3 bg-green-600/30 rounded-xl hover:bg-green-600/40 transition">
                            <i class="ri-whatsapp-line text-xl"></i>
                            <span>Chat on WhatsApp</span>
                        </a>
                    </div>
                    
                    <button onclick="openInquiryModal()" 
                            class="w-full py-3 bg-white text-gray-900 font-semibold rounded-xl hover:shadow-lg transition">
                        <i class="ri-message-2-line mr-2"></i> Send Message
                    </button>
                </div>
                
                <!-- Property Summary -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Property Summary</h3>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Property ID</span>
                            <span class="font-medium">#{{ str_pad($property->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Type</span>
                            <span class="font-medium">{{ ucfirst($property->property_type) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Listing</span>
                            <span class="font-medium">{{ $property->price_type === 'sale' ? 'For Sale' : 'For Rent' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium">{{ ucfirst($property->status) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Listed</span>
                            <span class="font-medium">{{ $property->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Views</span>
                            <span class="font-medium">{{ number_format($property->views) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Similar Properties -->
                @if(isset($similarProperties) && $similarProperties->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Similar Properties</h3>
                    
                    <div class="space-y-4">
                        @foreach($similarProperties->take(3) as $similar)
                        <a href="{{ route('properties.show', $similar->slug) }}" class="flex gap-3 group">
                            @php
                                $similarImage = $similar->primary_image ?? null;
                                $similarImageUrl = $similarImage ? route('file.show', ['path' => $similarImage]) : 'https://via.placeholder.com/80x80?text=Property';
                            @endphp
                            <img src="{{ $similarImageUrl }}" 
                                 alt="{{ $similar->title }}" 
                                 class="w-20 h-20 rounded-lg object-cover"
                                 onerror="this.src='https://via.placeholder.com/80x80?text=Property'">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition line-clamp-2 text-sm">
                                    {{ $similar->title }}
                                </h4>
                                <p class="text-blue-600 font-semibold text-sm mt-1">
                                    ETB {{ number_format($similar->price) }}
                                    @if($similar->price_type === 'rent')<span class="text-xs font-normal text-gray-500">/mo</span>@endif
                                </p>
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

<!-- Inquiry Modal -->
<div id="inquiryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Send Inquiry</h3>
                <button onclick="closeInquiryModal()" class="text-white/80 hover:text-white">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <p class="text-blue-100 text-sm">Interested in this property? Send a message.</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('properties.inquiry', $property) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Your Name" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="email" name="email" placeholder="Email Address" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="tel" name="phone" placeholder="Phone Number" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <textarea name="message" rows="4" placeholder="I'm interested in this property. Please contact me with more information." required
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">I'm interested in {{ $property->title }}. Please contact me with more information.</textarea>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Send Inquiry
                        </button>
                        <button type="button" onclick="closeInquiryModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Fancybox with all images
        const images = [
            @foreach($propertyImages as $image)
                { src: '{{ route('file.show', ['path' => $image->image_path]) }}', caption: '{{ $property->title }}' },
            @endforeach
        ];
        
        window.propertyGallery = images;
        
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: { autoStart: true },
            Toolbar: { display: ['counter', 'zoom', 'slideshow', 'fullscreen', 'close'] }
        });
        
        // Initialize Swiper
        @if($imageCount > 0)
        const galleryThumbs = new Swiper('.gallery-thumbs', {
            slidesPerView: 3,
            direction: 'vertical',
            spaceBetween: 8,
            watchSlidesProgress: true
        });
        
        const galleryMain = new Swiper('.gallery-main', {
            loop: {{ $imageCount > 1 ? 'true' : 'false' }},
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
            thumbs: { swiper: galleryThumbs },
            autoplay: { delay: 4000, disableOnInteraction: false }
        });
        
        // Make main gallery slides clickable
        document.querySelectorAll('.gallery-main .swiper-slide').forEach((slide) => {
            slide.addEventListener('click', function() {
                openFancybox(galleryMain.realIndex);
            });
        });
        @endif
    });
    
    function openFancybox(index) {
        if (window.propertyGallery && window.propertyGallery.length > 0) {
            Fancybox.show(window.propertyGallery, { startIndex: index });
        }
    }
    
    function openInquiryModal() {
        const modal = document.getElementById('inquiryModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeInquiryModal() {
        const modal = document.getElementById('inquiryModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeInquiryModal();
    });
    
    document.getElementById('inquiryModal').addEventListener('click', function(e) {
        if (e.target === this) closeInquiryModal();
    });
    
    function toggleFavorite(propertyId) {
        @auth
        fetch(`/properties/${propertyId}/favorite`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const btn = document.querySelector('.favorite-btn i');
            const btnText = document.querySelector('.favorite-btn span');
            
            if (data.status === 'added') {
                btn.classList.remove('ri-heart-line');
                btn.classList.add('ri-heart-fill', 'text-red-500');
                if (btnText) btnText.textContent = 'Saved';
                showToast('Property saved to favorites!', 'success');
            } else if (data.status === 'removed') {
                btn.classList.remove('ri-heart-fill', 'text-red-500');
                btn.classList.add('ri-heart-line');
                if (btnText) btnText.textContent = 'Save';
                showToast('Property removed from favorites', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Something went wrong. Please try again.', 'error');
        });
        @else
        window.location.href = '{{ route("login") }}?redirect={{ url()->current() }}';
        @endauth
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-24 left-1/2 transform -translate-x-1/2 px-5 py-3 rounded-lg text-white text-sm font-medium shadow-lg z-50 ${
            type === 'error' ? 'bg-red-600' : 'bg-green-600'
        }`;
        toast.textContent = message;
        toast.style.animation = 'fadeIn 0.3s ease';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    function shareProperty() {
        const url = window.location.href;
        const title = '{{ $property->title }}';
        
        if (navigator.share) {
            navigator.share({ 
                title: title, 
                text: 'Check out this property: ' + title,
                url: url 
            }).catch(() => {
                // User cancelled
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                showToast('Link copied to clipboard!', 'success');
            }).catch(() => {
                prompt('Copy this link:', url);
            });
        }
    }
</script>
@endpush