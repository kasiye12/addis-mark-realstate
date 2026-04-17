@extends('layouts.frontend')

@section('title', 'Properties - Addis Mark Real Estate')

@section('content')
    <section class="pt-32 pb-20 bg-gradient-to-br from-blue-600 to-indigo-900">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-4">Find Your Perfect Property</h1>
                <p class="text-xl text-white/80">Explore our exclusive collection of premium properties</p>
            </div>
        </div>
    </section>
    
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <form action="{{ route('properties.index') }}" method="GET" class="grid md:grid-cols-4 gap-4">
                    <select name="category" class="px-4 py-2 border rounded-lg">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="price_type" class="px-4 py-2 border rounded-lg">
                        <option value="">All Types</option>
                        <option value="sale" {{ request('price_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ request('price_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                    </select>
                    
                    <select name="sort" class="px-4 py-2 border rounded-lg">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                    
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Apply Filters
                    </button>
                </form>
            </div>
            
            <!-- Properties Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $property)
                    @include('frontend.properties.partials.property-card', ['property' => $property])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No properties found matching your criteria.</p>
                        <a href="{{ route('properties.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">
                            Clear all filters
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($properties->hasPages())
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
            @endif
        </div>
    </section>
@endsection