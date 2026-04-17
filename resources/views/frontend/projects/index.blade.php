@extends('layouts.frontend')

@section('title', 'Projects & Developments - ' . setting('site_name', 'Addis Mark Real Estate'))

@push('styles')
<style>
    .project-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .project-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }
    .project-card:hover img {
        transform: scale(1.08);
    }
    .status-tab {
        transition: all 0.2s ease;
    }
    .status-tab:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4">
            <i class="ri-building-2-line mr-2"></i>Our Portfolio
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
            Projects & Developments
        </h1>
        <p class="text-xl text-white/80 max-w-2xl mx-auto">
            Discover our ongoing, completed, and upcoming real estate projects across Ethiopia
        </p>
    </div>
</section>

<!-- Status Tabs -->
<section class="bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center -mb-px">
            <a href="{{ route('projects.index', ['status' => 'ongoing']) }}" 
               class="status-tab inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('status', 'ongoing') == 'ongoing' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-building-line text-lg"></i>
                <span>Ongoing Projects</span>
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status', 'ongoing') == 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['ongoing'] ?? 0 }}
                </span>
            </a>
            <a href="{{ route('projects.index', ['status' => 'completed']) }}" 
               class="status-tab inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('status') == 'completed' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-checkbox-circle-line text-lg"></i>
                <span>Completed Projects</span>
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['completed'] ?? 0 }}
                </span>
            </a>
            <a href="{{ route('projects.index', ['status' => 'upcoming']) }}" 
               class="status-tab inline-flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition
                      {{ request('status') == 'upcoming' ? 'border-amber-600 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="ri-calendar-line text-lg"></i>
                <span>Upcoming Projects</span>
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ request('status') == 'upcoming' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['upcoming'] ?? 0 }}
                </span>
            </a>
        </div>
    </div>
</section>

<!-- Projects Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($projects->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects as $project)
                <div class="project-card bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $project->featured_image_url }}" 
                             alt="{{ $project->title }}" 
                             class="w-full h-full object-cover transition duration-700">
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $project->status_badge[0] }}">
                                <i class="ri-{{ $project->status == 'ongoing' ? 'loader-4-line animate-spin' : ($project->status == 'completed' ? 'check-line' : 'calendar-line') }} mr-1"></i>
                                {{ $project->status_badge[1] }}
                            </span>
                        </div>
                        
                        @if($project->is_featured)
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-semibold rounded-full shadow-sm">
                                <i class="ri-star-fill mr-1"></i>Featured
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                            <i class="ri-map-pin-line text-blue-600"></i>
                            <span>{{ $project->location }}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-1">
                            <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-blue-600 transition">
                                {{ $project->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $project->short_description ?? Str::limit($project->description, 100) }}
                        </p>
                        
                        <!-- Details -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            @if($project->starting_price)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Starting From</p>
                                <p class="text-xl font-bold text-blue-600">ETB {{ number_format($project->starting_price) }}</p>
                            </div>
                            @endif
                            
                            <a href="{{ route('projects.show', $project->slug) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                View Details
                                <i class="ri-arrow-right-line ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $projects->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-building-2-line text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No projects found</h3>
                <p class="text-gray-500">Check back later for new developments.</p>
            </div>
        @endif
    </div>
</section>
@endsection