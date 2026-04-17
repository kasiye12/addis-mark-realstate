@extends('layouts.frontend')

@section('title', $property->title . ' - Addis Mark Real Estate')

@section('content')
    <section class="pt-24 pb-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('properties.index') }}" class="text-gray-500 hover:text-blue-600">Properties</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900">{{ $property->title }}</span>
            </nav>
            
            <!-- Title and Price -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <i class="ri-map-pin-line text-blue-600"></i>
                        {{ $property->location->full_address ?? $property->address ?? 'Location not specified' }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-blue-600">
                        @if($property->price_type === 'rent')
                            ETB {{ number_format($property->price) }} / month
                        @else
                            ETB {{ number_format($property->price) }}
                        @endif
                    </div>
                    <span class="text-sm text-gray-500">{{ ucfirst($property->price_type) }}</span>
                </div>
            </div>
            
            <!-- Main Image -->
            <div class="mb-8">
                @php
                    $imagePath = $property->primary_image ?? 'images/default-property.jpg';
                @endphp
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="{{ $property->title }}" 
                     class="w-full h-96 object-cover rounded-2xl"
                     onerror="this.src='https://via.placeholder.com/1200x400?text=Property+Image'">
            </div>
            
            <!-- Details -->
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <!-- Overview -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Overview</h2>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @if($property->bedrooms)
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $property->bedrooms }}</div>
                                <div class="text-sm text-gray-500">Bedrooms</div>
                            </div>
                            @endif
                            @if($property->bathrooms)
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $property->bathrooms }}</div>
                                <div class="text-sm text-gray-500">Bathrooms</div>
                            </div>
                            @endif
                            @if($property->area_sqm)
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $property->area_sqm }}</div>
                                <div class="text-sm text-gray-500">Area (m²)</div>
                            </div>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold mb-2">Description</h3>
                        <p class="text-gray-600">{{ $property->description }}</p>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold mb-4">Contact Agent</h3>
                        
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('properties.inquiry', $property) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="text" name="name" placeholder="Your Name" required
                                       class="w-full px-4 py-2 border rounded-lg">
                                <input type="email" name="email" placeholder="Your Email" required
                                       class="w-full px-4 py-2 border rounded-lg">
                                <input type="tel" name="phone" placeholder="Phone Number" required
                                       class="w-full px-4 py-2 border rounded-lg">
                                <textarea name="message" rows="3" placeholder="I'm interested in this property..."
                                          class="w-full px-4 py-2 border rounded-lg"></textarea>
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                                    Send Inquiry
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection