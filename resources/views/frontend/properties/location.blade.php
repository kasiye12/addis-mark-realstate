@extends('layouts.frontend')

@php
    $siteName = \App\Models\Setting::get('site_name', 'Addis Mark Real Estate');
    $locationImage = $location->image ?? null;
    $locationImageUrl = $locationImage ? route('file.show', ['path' => $locationImage]) : null;
@endphp

@section('title', 'Properties in ' . $location->area_name . ', ' . $location->city . ' - ' . $siteName)
@section('meta_description', 'Discover premium properties in ' . $location->area_name . ', ' . $location->city . '. Browse our exclusive collection of real estate listings in this prime location.')
@section('meta_keywords', $location->area_name . ', ' . $location->city . ', real estate, properties, Ethiopia, ' . $location->area_name . ' properties')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    /* Hero Section */
    .location-hero {
        position: relative;
        height: 400px;
        background-size: cover;
        background-position: center;
    }
    
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
    }
    
    /* Stats Card */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Property Card */
    .property-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    
    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.15);
    }
    
    .property-card img {
        transition: transform 0.5s ease;
    }
    
    .property-card:hover img {
        transform: scale(1.05);
    }
    
    /* Map Container */
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
        height: 400px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    
    /* Filter Bar */
    .filter-bar {
        background: white;
        border-radius: 60px;
        padding: 8px;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
        border: 1px solid #f0f0f0;
    }
    
    .filter-select {
        padding: 12px 20px;
        border-radius: 40px;
        border: none;
        background: transparent;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-select:hover {
        background: #f3f4f6;
    }
    
    .filter-select:focus {
        outline: none;
        background: #eff6ff;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="location-hero" style="background-image: url('{{ $locationImageUrl ?? 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg' }}');">
    <div class="hero-overlay"></div>
    
    <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-12">
        <div class="text-white" data-aos="fade-up">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm mb-4 text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i class="ri-arrow-right-s-line mx-2"></i>
                <a href="{{ route('properties.index') }}" class="hover:text-white transition">Properties</a>
                <i class="ri-arrow-right-s-line mx-2"></i>
                <span class="text-white">{{ $location->area_name }}</span>
            </nav>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold mb-3">
                Properties in {{ $location->area_name }}
            </h1>
            
            <!-- Location Info -->
            <div class="flex flex-wrap items-center gap-4 text-white/90">
                <span class="flex items-center gap-2">
                    <i class="ri-map-pin-line"></i>
                    {{ $location->sub_city ? $location->sub_city . ', ' : '' }}{{ $location->city }}
                </span>
                @if($location->is_popular)
                <span class="flex items-center gap-2 bg-amber-500/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">
                    <i class="ri-star-fill text-amber-400"></i>
                    Popular Area
                </span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
                <i class="ri-building-line text-3xl text-blue-600 mb-2"></i>
                <p class="text-2xl font-bold text-gray-900">{{ $properties->total() }}</p>
                <p class="text-sm text-gray-500">Total Properties</p>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <i class="ri-home-4-line text-3xl text-green-600 mb-2"></i>
                @php
                    $forSale = $properties->where('price_type', 'sale')->count();
                @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $forSale }}</p>
                <p class="text-sm text-gray-500">For Sale</p>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <i class="ri-key-line text-3xl text-purple-600 mb-2"></i>
                @php
                    $forRent = $properties->where('price_type', 'rent')->count();
                @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $forRent }}</p>
                <p class="text-sm text-gray-500">For Rent</p>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <i class="ri-price-tag-3-line text-3xl text-amber-600 mb-2"></i>
                @php
                    $avgPrice = $properties->avg('price') ?? 0;
                @endphp
                <p class="text-2xl font-bold text-gray-900">ETB {{ number_format($avgPrice / 1000000, 1) }}M</p>
                <p class="text-sm text-gray-500">Average Price</p>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
@if($location->latitude && $location->longitude)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Location</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Explore the Area</h2>
            <p class="text-gray-600 mt-2">{{ $location->area_name }}, {{ $location->city }}</p>
        </div>
        
        <div class="map-container" data-aos="zoom-in">
            <iframe 
                src="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}&z=14&output=embed"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <div class="text-center mt-4">
            <a href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
               target="_blank"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 transition">
                <i class="ri-external-link-line"></i>
                View Larger Map
            </a>
        </div>
    </div>
</section>
@endif

<!-- Filters Section -->
<section class="py-8 bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="container mx-auto px-4">
        <form method="GET" action="{{ route('properties.location', $location->slug) }}" class="filter-bar flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-2">
                <select name="price_type" class="filter-select">
                    <option value="">All Listings</option>
                    <option value="sale" {{ request('price_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="rent" {{ request('price_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                </select>
                
                <select name="property_type" class="filter-select">
                    <option value="">All Types</option>
                    <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    <option value="commercial" {{ request('property_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="land" {{ request('property_type') == 'land' ? 'selected' : '' }}>Land</option>
                    <option value="office" {{ request('property_type') == 'office' ? 'selected' : '' }}>Office</option>
                </select>
                
                <select name="bedrooms" class="filter-select">
                    <option value="">Any Beds</option>
                    <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+ Bed</option>
                    <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+ Beds</option>
                    <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+ Beds</option>
                    <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+ Beds</option>
                    <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+ Beds</option>
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <select name="sort" class="filter-select">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
                
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                    <i class="ri-filter-line mr-1"></i> Filter
                </button>
                
                @if(request()->anyFilled(['price_type', 'property_type', 'bedrooms', 'sort']))
                <a href="{{ route('properties.location', $location->slug) }}" class="px-4 py-3 text-gray-500 hover:text-gray-700 transition">
                    <i class="ri-close-line"></i> Clear
                </a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- Properties Grid -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($properties->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <div class="property-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <!-- Property Image -->
                    <div class="relative h-56 overflow-hidden">
                        @php
                            $imagePath = $property->primary_image;
                            $imageUrl = $imagePath ? route('file.show', ['path' => $imagePath]) : 'https://via.placeholder.com/400x300?text=Property';
                        @endphp
                        <a href="{{ route('properties.show', $property->slug) }}">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://via.placeholder.com/400x300?text=Property'">
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex gap-2">
                            <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                {{ ucfirst($property->price_type) }}
                            </span>
                            @if($property->is_featured)
                            <span class="px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                                <i class="ri-star-fill mr-1"></i>Featured
                            </span>
                            @endif
                        </div>
                        
                        <!-- Price -->
                        <div class="absolute bottom-3 right-3">
                            <div class="bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow">
                                <span class="text-lg font-bold text-gray-900">
                                    ETB {{ number_format($property->price) }}
                                    @if($property->price_type === 'rent')<span class="text-sm font-normal">/mo</span>@endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property Details -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                            <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-blue-600 transition">
                                {{ $property->title }}
                            </a>
                        </h3>
                        
                        <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                            <i class="ri-map-pin-line text-blue-600"></i>
                            {{ $property->location->area_name ?? $location->area_name }}
                        </p>
                        
                        <!-- Features -->
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                            @if($property->bedrooms)
                            <span class="flex items-center gap-1">
                                <i class="ri-hotel-bed-line"></i> {{ $property->bedrooms }}
                            </span>
                            @endif
                            @if($property->bathrooms)
                            <span class="flex items-center gap-1">
                                <i class="ri-drop-line"></i> {{ $property->bathrooms }}
                            </span>
                            @endif
                            @if($property->area_sqm)
                            <span class="flex items-center gap-1">
                                <i class="ri-ruler-line"></i> {{ $property->area_sqm }} m²
                            </span>
                            @endif
                        </div>
                        
                        <!-- Agent & Action -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-sm text-gray-500">
                                <i class="ri-user-line mr-1"></i>
                                {{ $property->user->name ?? 'Addis Mark Agent' }}
                            </span>
                            <a href="{{ route('properties.show', $property->slug) }}" 
                               class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                View Details
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-10">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-building-line text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties found</h3>
                <p class="text-gray-500 mb-6">We couldn't find any properties matching your criteria in {{ $location->area_name }}.</p>
                <a href="{{ route('properties.location', $location->slug) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Nearby Locations -->
@php
    $nearbyLocations = \App\Models\Location::where('city', $location->city)
        ->where('id', '!=', $location->id)
        ->where('is_popular', true)
        ->take(4)
        ->get();
@endphp

@if($nearbyLocations->count() > 0)
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Explore Nearby</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Other Popular Areas</h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($nearbyLocations as $nearby)
            <a href="{{ route('properties.location', $nearby->slug) }}" 
               class="group relative h-48 rounded-xl overflow-hidden shadow-lg"
               data-aos="fade-up"
               data-aos-delay="{{ $loop->index * 100 }}">
                <img src="https://picsum.photos/400/300?random={{ $loop->index }}" 
                     alt="{{ $nearby->area_name }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                    <h3 class="font-bold text-lg">{{ $nearby->area_name }}</h3>
                    <p class="text-sm text-white/80">{{ $nearby->properties_count ?? 0 }} Properties</p>
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
        <div data-aos="zoom-in">
            <h2 class="text-3xl font-bold text-white mb-4">
                Want to List Your Property in {{ $location->area_name }}?
            </h2>
            <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">
                Join thousands of satisfied property owners who trust Addis Mark Real Estate.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg">
                    Contact an Agent
                </a>
                <a href="{{ route('properties.index') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition">
                    Browse All Properties
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
        AOS.init({ duration: 800, once: true, offset: 50 });
    });
</script>
@endpush