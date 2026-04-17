<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" 
     data-aos="fade-up" 
     data-aos-delay="{{ $loop->index * 50 ?? 0 }}">
    <!-- Property Image -->
    <div class="relative h-56 overflow-hidden">
        <img src="{{ asset($property->primary_image) }}" 
             alt="{{ $property->title }}" 
             class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
        
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex gap-2">
            <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                {{ ucfirst($property->price_type) }}
            </span>
            @if($property->is_featured)
                <span class="px-3 py-1 bg-yellow-400 text-gray-900 text-xs font-semibold rounded-full">
                    Featured
                </span>
            @endif
        </div>
        
        <!-- Price -->
        <div class="absolute bottom-4 right-4">
            <div class="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg">
                <span class="text-lg font-bold text-gray-900">{{ $property->formatted_price }}</span>
            </div>
        </div>
    </div>
    
    <!-- Property Details -->
    <div class="p-5">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <i class="ri-map-pin-line text-blue-600"></i>
            <span class="line-clamp-1">{{ $property->location->full_address }}</span>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
            <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-blue-600 transition">
                {{ $property->title }}
            </a>
        </h3>
        
        <!-- Features -->
        <div class="flex items-center gap-3 text-xs text-gray-600 mb-4">
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
        
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-500">
                <i class="ri-time-line mr-1"></i>{{ $property->created_at->diffForHumans() }}
            </span>
            
            <a href="{{ route('properties.show', $property->slug) }}" 
               class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-1">
                Details <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>
</div>