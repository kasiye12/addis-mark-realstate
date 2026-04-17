@extends('layouts.frontend')

@section('title', 'Our Services - Addis Mark Real Estate')

@section('content')
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-blue-600 to-indigo-900">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-4">Our Services</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">
                    Comprehensive real estate solutions tailored to your needs
                </p>
            </div>
        </div>
    </section>
    
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="ri-home-4-line text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Property Sales</h3>
                    <p class="text-gray-600">Expert guidance in buying and selling residential and commercial properties.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="ri-key-line text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Property Rentals</h3>
                    <p class="text-gray-600">Find the perfect rental property with our extensive portfolio and expertise.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="ri-building-2-line text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Property Management</h3>
                    <p class="text-gray-600">Full-service property management for landlords and investors.</p>
                </div>
            </div>
        </div>
    </section>
@endsection