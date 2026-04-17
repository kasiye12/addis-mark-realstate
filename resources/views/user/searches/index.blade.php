@extends('layouts.app')

@section('title', 'Saved Searches')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Saved Searches</h1>
            <p class="text-gray-600 mt-1">Your saved property search criteria</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if(isset($searches) && $searches->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($searches as $search)
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $search->name ?? 'Custom Search' }}</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $search->location ?? 'All Locations' }} • 
                                {{ $search->property_type ?? 'All Types' }} • 
                                {{ $search->bedrooms ? $search->bedrooms . '+ beds' : 'Any beds' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('properties.index', json_decode($search->criteria, true)) }}" 
                               class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded">
                                Apply
                            </a>
                            <form action="{{ route('user.properties.searches.delete', $search) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="ri-bookmark-line text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No saved searches</h3>
                    <p class="text-gray-500 mb-4">Save your search criteria for quick access later.</p>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="ri-search-line mr-2"></i> Search Properties
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection