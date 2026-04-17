@extends('layouts.frontend')

@section('title', setting('site_name', 'Addis Mark Real Estate') . ' - Premium Properties in Ethiopia')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    /* Full Screen Hero Slider */
    .hero-slider {
        position: relative;
        width: 100%;
        height: 100vh;
        min-height: 700px;
    }
    
    .hero-slider .swiper-slide {
        position: relative;
        overflow: hidden;
    }
    
    .hero-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        animation: slowZoom 20s ease-in-out infinite alternate;
    }
    
    @keyframes slowZoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.2); }
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.2) 100%);
        z-index: 1;
    }
    
    .hero-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
        display: flex;
        align-items: center;
    }
    
    /* Stats Cards */
    .stat-item {
        text-align: left;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #fde047;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* Text Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .animate-fadeInLeft {
        animation: fadeInLeft 0.8s ease-out forwards;
    }
    
    .animate-fadeInRight {
        animation: fadeInRight 0.8s ease-out forwards;
    }
    
    /* Property Card */
    .property-card {
        transition: all 0.3s ease;
    }
    
    .property-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    
    .property-card:hover img {
        transform: scale(1.08);
    }
    
    .property-card img {
        transition: transform 0.5s ease;
    }
    
    /* Category Card */
    .category-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .category-card:hover {
        background: #2563eb;
        border-color: #2563eb;
        transform: translateY(-5px);
    }
    
    .category-card:hover i,
    .category-card:hover h3,
    .category-card:hover p {
        color: white !important;
    }
    
    /* Location Card */
    .location-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
    }
    
    .location-card img {
        transition: transform 0.6s ease;
    }
    
    .location-card:hover img {
        transform: scale(1.1);
    }
    
    .location-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%);
    }
    /* Text shadow for better readability */
.drop-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.drop-shadow-md {
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
}

.drop-shadow-lg {
    text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
}

/* Location card text shadow */
.location-card .drop-shadow {
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
}
</style>
@endpush

@section('content')
    
<!-- Full Screen Hero Slider with Text Overlay -->
<section class="hero-slider">
    <div class="swiper heroSwiper h-full">
        <div class="swiper-wrapper">
            @php
                $heroImages = [];
                
                // Get images from featured properties
                if(isset($featuredProperties) && $featuredProperties->count() > 0) {
                    foreach($featuredProperties->take(4) as $property) {
                        $imagePath = $property->primary_image;
                        if($imagePath) {
                            $heroImages[] = route('file.show', ['path' => $imagePath]);
                        }
                    }
                }
                
                // Fallback images
                if(count($heroImages) == 0) {
                    $heroImages = [
                        'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg',
                        'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
                        'https://images.pexels.com/photos/280229/pexels-photo-280229.jpeg',
                        'https://images.pexels.com/photos/2587054/pexels-photo-2587054.jpeg',
                    ];
                }
            @endphp
            
            @foreach($heroImages as $image)
            <div class="swiper-slide">
                <img src="{{ $image }}" alt="Luxury Property">
                <div class="hero-overlay"></div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="swiper-pagination !bottom-8"></div>
    </div>
    
    <!-- Hero Content Overlay -->
    <div class="hero-content">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 animate-fadeInLeft" style="animation-delay: 0.1s">
                        Find Your Dream <br>
                        <span class="text-yellow-300">Property in Ethiopia</span>
                    </h1>
                    
                    <p class="text-lg text-white/90 mb-8 max-w-lg animate-fadeInLeft" style="animation-delay: 0.3s">
                        Discover premium properties in Addis Ababa and beyond. 
                        From luxury villas to modern apartments.
                    </p>
                    
                    <!-- Stats -->
                    <div class="flex gap-8 animate-fadeInUp" style="animation-delay: 0.5s">
                        <div class="stat-item">
                            <div class="stat-number">{{ number_format($stats['properties'] ?? 0) }}+</div>
                            <div class="stat-label">Properties</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ number_format($stats['locations'] ?? 0) }}+</div>
                            <div class="stat-label">Locations</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ number_format($stats['clients'] ?? 1250) }}+</div>
                            <div class="stat-label">Happy Clients</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Search Form -->
                <div class="lg:ml-auto animate-fadeInRight" style="animation-delay: 0.3s">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full">
                        <h2 class="text-xl font-bold text-gray-900 mb-5">
                            Find Your Perfect Property
                        </h2>
                        
                        <form action="{{ route('properties.index') }}" method="GET" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
                                <select name="location" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">All Locations ▼</option>
                                    @foreach($popularLocations as $location)
                                        <option value="{{ $location->slug }}">{{ $location->area_name }}, {{ $location->city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Property Type</label>
                                <select name="property_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">All Types ▼</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="villa">Villa</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="land">Land</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Min Price</label>
                                    <select name="min_price" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white">
                                        <option value="">No Min ▼</option>
                                        <option value="1000000">1M ETB</option>
                                        <option value="3000000">3M ETB</option>
                                        <option value="5000000">5M ETB</option>
                                        <option value="10000000">10M ETB</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Max Price</label>
                                    <select name="max_price" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white">
                                        <option value="">No Max ▼</option>
                                        <option value="3000000">3M ETB</option>
                                        <option value="5000000">5M ETB</option>
                                        <option value="10000000">10M ETB</option>
                                        <option value="20000000">20M ETB</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bedrooms</label>
                                <select name="bedrooms" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white">
                                    <option value="">Any ▼</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                    <option value="5">5+</option>
                                </select>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                Search Properties
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 hidden lg:block">
        <a href="#featured-properties" class="flex flex-col items-center gap-2 text-white/80 hover:text-white transition group">
            <span class="text-xs uppercase tracking-wider">Scroll</span>
            <div class="w-5 h-8 border-2 border-white/50 rounded-full flex justify-center group-hover:border-white">
                <div class="w-1.5 h-1.5 bg-white rounded-full mt-1.5 animate-bounce"></div>
            </div>
        </a>
    </div>
</section>

<!-- Featured Properties Section -->
<section id="featured-properties" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Premium Selection</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-3">
                Featured Properties
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Hand-picked luxury properties that represent the finest real estate opportunities
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredProperties as $property)
                <div class="property-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative h-56 overflow-hidden">
                        @php
                            $imagePath = $property->primary_image;
                            $imageUrl = $imagePath ? route('file.show', ['path' => $imagePath]) : asset('images/default-property.jpg');
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                        
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                {{ ucfirst($property->price_type) }}
                            </span>
                        </div>
                        
                        <div class="absolute bottom-3 right-3">
                            <div class="bg-white px-3 py-1.5 rounded-lg shadow">
                                <span class="text-lg font-bold text-gray-900">ETB {{ number_format($property->price) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex items-center gap-1 text-sm text-gray-500 mb-2">
                            <i class="ri-map-pin-line text-blue-600"></i>
                            <span>{{ $property->location->area_name ?? 'Location' }}</span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                            <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-blue-600">{{ $property->title }}</a>
                        </h3>
                        
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                            @if($property->bedrooms)
                                <span><i class="ri-hotel-bed-line"></i> {{ $property->bedrooms }}</span>
                            @endif
                            @if($property->bathrooms)
                                <span><i class="ri-drop-line"></i> {{ $property->bathrooms }}</span>
                            @endif
                            @if($property->area_sqm)
                                <span><i class="ri-ruler-line"></i> {{ $property->area_sqm }} m²</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-sm text-gray-500">Addis Mark Agent</span>
                            <a href="{{ route('properties.show', $property->slug) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                Details <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No featured properties available.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('properties.index') }}" 
               class="inline-flex items-center px-6 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition">
                View All Properties
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Video Section -->
@php
    $homepageVideo = \App\Models\Setting::get('homepage_video');
    $homepageVideoPoster = \App\Models\Setting::get('homepage_video_poster');
    $videoUrl = $homepageVideo ? route('file.show', ['path' => $homepageVideo]) : null;
    $posterUrl = $homepageVideoPoster ? route('file.show', ['path' => $homepageVideoPoster]) : null;
@endphp

@if($videoUrl)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Virtual Tour</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-3">Experience Luxury Living</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Take a virtual journey through Ethiopia's most exclusive properties</p>
        </div>
        
        <div class="max-w-4xl mx-auto" data-aos="zoom-in">
            <div class="relative rounded-2xl overflow-hidden shadow-xl">
                <video controls poster="{{ $posterUrl }}" class="w-full">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Browse By Category</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-3">Find Your Property Type</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explore our diverse portfolio of properties across different categories</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('properties.category', $category->slug) }}" 
                   class="category-card bg-white rounded-xl p-5 text-center"
                   data-aos="fade-up"
                   data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="ri-home-4-line text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->active_properties_count ?? 0 }} Properties</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Locations -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Prime Locations</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-3">Popular Areas in Addis Ababa</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Discover properties in the most sought-after neighborhoods</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popularLocations->take(4) as $location)
                <a href="{{ route('properties.location', $location->slug) }}" 
                   class="location-card group relative h-64"
                   data-aos="fade-up"
                   data-aos-delay="{{ $loop->index * 100 }}">
                    @php
                        // Check if location has image, otherwise use fallback
                        if ($location->image && \Storage::disk('public')->exists($location->image)) {
                            $locationImage = route('file.show', ['path' => $location->image]) . '?v=' . time();
                        } else {
                            // Fallback images based on index
                            $fallbackImages = [
                                'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg',
                                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
                                'https://images.pexels.com/photos/280229/pexels-photo-280229.jpeg',
                                'https://images.pexels.com/photos/2587054/pexels-photo-2587054.jpeg',
                            ];
                            $locationImage = $fallbackImages[$loop->index] ?? $fallbackImages[0];
                        }
                    @endphp
                    <img src="{{ $locationImage }}" 
                         alt="{{ $location->area_name }}" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg'">
                    <div class="location-overlay absolute inset-0"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                        <h3 class="text-xl font-bold drop-shadow-lg">{{ $location->area_name }}</h3>
                        <p class="text-sm opacity-90 drop-shadow">{{ $location->active_properties_count ?? 0 }} Properties</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Our Advantages</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">Why Choose Addis Mark Real Estate</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Experience excellence in Ethiopian real estate with our premium services</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="text-center group p-6 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all">
                    <i class="ri-shield-check-line text-3xl text-blue-600 group-hover:text-white transition-all"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Trusted & Verified</h3>
                <p class="text-gray-600 text-sm leading-relaxed">All properties are verified and listings are accurate</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="text-center group p-6 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all">
                    <i class="ri-customer-service-2-line text-3xl text-blue-600 group-hover:text-white transition-all"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Dedicated support team available around the clock</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="text-center group p-6 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all">
                    <i class="ri-bar-chart-2-line text-3xl text-blue-600 group-hover:text-white transition-all"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Market Expertise</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $stats['experience'] ?? 15 }}+ years in real estate</p>
            </div>
            
            <!-- Feature 4 -->
            <div class="text-center group p-6 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all">
                    <i class="ri-secure-payment-line text-3xl text-blue-600 group-hover:text-white transition-all"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Secure Transactions</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Safe and transparent property transactions</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="container mx-auto px-4 text-center">
        <div data-aos="zoom-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Ready to Find Your Dream Property?</h2>
            <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">Let our experts help you find the perfect property in Ethiopia's prime locations</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('properties.index') }}" class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg">
                    Browse Properties
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({ duration: 800, once: true, offset: 50 });
        
        // Hero Slider with Autoplay
        new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            }
        });
    });
</script>
@endpush