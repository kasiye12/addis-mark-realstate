@extends('layouts.frontend')

@section('title', 'Properties in ' . $location->area_name . ' - Addis Mark Real Estate')

@section('content')
    <section class="pt-32 pb-20 bg-gradient-to-br from-blue-600 to-indigo-900">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-4">Properties in {{ $location->area_name }}</h1>
                <p class="text-xl text-white/80">{{ $location->full_address }}</p>
            </div>
        </div>
    </section>
    
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $property)
                    @include('frontend.properties.partials.property-card', ['property' => $property])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No properties found in this location.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
        </div>
    </section>
@endsection