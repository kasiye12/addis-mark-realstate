@extends('layouts.app')

@section('title', 'My Inquiries')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Inquiries</h1>
            <p class="text-gray-600 mt-1">Track your property inquiries</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if(isset($inquiries) && $inquiries->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($inquiries as $inquiry)
                        <tr>
                            <td class="px-6 py-4">
                                <a href="{{ route('properties.show', $inquiry->property->slug) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $inquiry->property->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ Str::limit($inquiry->message, 40) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $inquiry->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <i class="ri-message-2-line text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No inquiries yet</h3>
                    <p class="text-gray-500">Your property inquiries will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection