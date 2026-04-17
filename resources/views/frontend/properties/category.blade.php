@extends('layouts.frontend')

@php
    $siteName = \App\Models\Setting::get('site_name', 'Addis Mark Real Estate');
    $categoryImage = $category->image ?? null;
    $categoryImageUrl = $categoryImage ? route('file.show', ['path' => $categoryImage]) : null;
@endphp

@section('title', $category->name . ' Properties - ' . $siteName)
@section('meta_description', 'Browse our exclusive collection of ' . $category->name . ' in Ethiopia. ' . ($category->description ?? 'Find your perfect ' . $category->name . ' today.'))
@section('meta_keywords', $category->name . ', properties, real estate, Ethiopia, ' . $category->name . ' for sale, ' . $category->name . ' for rent')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    /* Hero Section */
    .category-hero {
        position: relative;
        height: 350px;
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
    
    /* Category Description */
    .category-description {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #e0e7ff;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="category-hero" style="background-image: url('{{ $categoryImageUrl ?? 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg' }}');">
    <div class="hero-overlay"></div>
    
    <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-12">
        <div class="text-white" data-aos="fade-up">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm mb-4 text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i class="ri-arrow-right-s-line mx-2"></i>
                <a href="{{ route('properties.index') }}" class="hover:text-white transition">Properties</a>
                <i class="ri-arrow-right-s-line mx-2"></i>
                <span class="text-white">{{ $category->name }}</span>
            </nav>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold mb-3">
                {{ $category->name }}
            </h1>
            
            <!-- Category Icon -->
            @if($category->icon)
            <div class="flex items-center gap-2">
                <i class="{{ $category->icon }} text-2xl"></i>
                <span class="text-white/90">Browse our exclusive collection</span>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Category Description -->
@if($category->description)
<section class="py-8 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="category-description max-w-4xl mx-auto" data-aos="fade-up">
            <p class="text-gray-700 text-lg leading-relaxed">{{ $category->description }}</p>
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
                <i class="ri-building-line text-3xl text-blue-600 mb-2"></i>
                <p class="text-2xl font-bold text-gray-900">{{ $properties->total() }}</p>
                <p class="text-sm text-gray-500">Total {{ $category->name }}</p>
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

<!-- Filters Section -->
<section class="py-8 bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="container mx-auto px-4">
        <form method="GET" action="{{ route('properties.category', $category->slug) }}" class="filter-bar flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-2">
                <select name="price_type" class="filter-select">
                    <option value="">All Listings</option>
                    <option value="sale" {{ request('price_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="rent" {{ request('price_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                </select>
                
                <select name="location" class="filter-select">
                    <option value="">All Locations</option>
                    @foreach($locations ?? [] as $loc)
                        <option value="{{ $loc->slug }}" {{ request('location') == $loc->slug ? 'selected' : '' }}>{{ $loc->area_name }}</option>
                    @endforeach
                </select>
                
                <select name="bedrooms" class="filter-select">
                    <option value="">Any Beds</option>
                    <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+ Bed</option>
                    <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+ Beds</option>
                    <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+ Beds</option>
                    <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+ Beds</option>
                    <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+ Beds</option>
                </select>
                
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}"
                           class="w-28 px-4 py-2 border border-gray-200 rounded-full text-sm">
                    <span class="text-gray-400">-</span>
                    <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}"
                           class="w-28 px-4 py-2 border border-gray-200 rounded-full text-sm">
                </div>
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
                
                @if(request()->anyFilled(['price_type', 'location', 'bedrooms', 'min_price', 'max_price', 'sort']))
                <a href="{{ route('properties.category', $category->slug) }}" class="px-4 py-3 text-gray-500 hover:text-gray-700 transition">
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
                            {{ $property->location->area_name ?? 'Location' }}
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
                <p class="text-gray-500 mb-6">We couldn't find any {{ $category->name }} matching your criteria.</p>
                <a href="{{ route('properties.category', $category->slug) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Related Categories -->
@php
    $relatedCategories = \App\Models\Category::where('is_active', true)
        ->where('id', '!=', $category->id)
        ->withCount('properties')
        ->take(4)
        ->get();
@endphp

@if($relatedCategories->count() > 0)
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Explore More</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Other Property Types</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedCategories as $related)
            <a href="{{ route('properties.category', $related->slug) }}" 
               class="group bg-white rounded-xl p-6 text-center border border-gray-200 hover:bg-blue-600 hover:border-blue-600 transition-all"
               data-aos="fade-up"
               data-aos-delay="{{ $loop->index * 100 }}">
                <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 group-hover:bg-white/20 rounded-xl flex items-center justify-center transition">
                    <i class="{{ $related->icon ?? 'ri-home-4-line' }} text-2xl text-blue-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-white">{{ $related->name }}</h3>
                <p class="text-sm text-gray-500 group-hover:text-white/80">{{ $related->properties_count }} Properties</p>
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
                Can't Find What You're Looking For?
            </h2>
            <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">
                Let our expert agents help you find the perfect {{ $category->name }} that meets your needs.
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