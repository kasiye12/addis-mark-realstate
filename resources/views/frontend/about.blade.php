@extends('layouts.frontend')

@section('title', 'About Us - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('meta_description', 'Learn about ' . setting('site_name', 'Addis Mark Real Estate') . ' - Ethiopia\'s trusted real estate agency with over 15 years of excellence.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    /* Stats Counter */
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        color: #2563eb;
    }
    
    /* Value Card */
    .value-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -8px rgba(0,0,0,0.12);
        border-color: #2563eb;
    }
    
    /* Timeline */
    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 30px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #2563eb;
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: 7px;
        top: 24px;
        width: 2px;
        height: calc(100% - 16px);
        background: linear-gradient(to bottom, #2563eb, #d1d5db);
    }
    .timeline-item:last-child::after {
        display: none;
    }
    
    /* Achievement Card */
    .achievement-card {
        background: white;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .achievement-card:hover {
        border-color: #2563eb;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.15);
        transform: translateY(-3px);
    }
    
    /* Section Title Styling */
    .section-label {
        color: #2563eb;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .section-heading {
        color: #111827;
        font-weight: 700;
    }
    
    /* Body Text */
    .text-body {
        color: #374151;
        line-height: 1.75;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="relative bg-blue-600 py-24 lg:py-32 overflow-hidden">
    <!-- Simple Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        {{-- Established Badge - White background with blue text --}}
        <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-full text-sm font-semibold mb-6 shadow-md" data-aos="fade-up">
            <i class="ri-calendar-line"></i>
            <span>Established 2010</span>
        </span>
        
        {{-- Main Heading - White text on blue background --}}
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6" data-aos="fade-up" data-aos-delay="100">
            About Addis Mark Real Estate
        </h1>
        
        {{-- Subtitle - White text on blue background --}}
        <p class="text-xl md:text-2xl text-white max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            Ethiopia's premier real estate agency, connecting people with their dream properties since 2010.
        </p>
    </div>
</section>

<!-- Company Overview -->
<section class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="section-label">Company Overview</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-6">
                    Your Trusted Partner in<br>Ethiopian Real Estate
                </h2>
                <div class="space-y-4">
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Founded in 2010, Addis Mark Real Estate has grown from a small local agency to become one of Ethiopia's most trusted names in property sales, rentals, and development.
                    </p>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        With over 15 years of experience in the Ethiopian real estate market, we have successfully helped thousands of clients find their perfect homes, secure profitable investments, and navigate the complexities of property transactions.
                    </p>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Our deep understanding of local markets, combined with international standards of service, ensures that every client receives exceptional guidance and support throughout their real estate journey.
                    </p>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-8">
                    <div>
                        <div class="stat-number">{{ number_format(\App\Models\Property::count()) }}+</div>
                        <p class="text-gray-700 text-sm font-medium">Properties Listed</p>
                    </div>
                    <div>
                        <div class="stat-number">{{ number_format(\App\Models\User::whereIn('role', ['admin', 'agent'])->count()) }}+</div>
                        <p class="text-gray-700 text-sm font-medium">Expert Agents</p>
                    </div>
                    <div>
                        <div class="stat-number">15+</div>
                        <p class="text-gray-700 text-sm font-medium">Years Experience</p>
                    </div>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left">
                <img src="https://images.pexels.com/photos/3184418/pexels-photo-3184418.jpeg" 
                     alt="Addis Mark Office" 
                     class="rounded-3xl shadow-2xl w-full h-auto">
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-5 hidden lg:block border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="ri-shield-check-line text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">100% Verified</p>
                            <p class="text-sm text-gray-600">All properties verified</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Mission -->
            <div class="value-card" data-aos="fade-up">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                    <i class="ri-flag-line text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Mission</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    To empower individuals and businesses in Ethiopia by providing transparent, efficient, and professional real estate services that exceed expectations.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Deliver exceptional value to our clients</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Maintain the highest standards of integrity</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Innovate and adapt to market changes</span>
                    </li>
                </ul>
            </div>
            
            <!-- Vision -->
            <div class="value-card" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-200">
                    <i class="ri-eye-line text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Vision</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                    To be Ethiopia's most respected and innovative real estate company, setting the standard for excellence and transforming the way people experience property.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Expand our presence across all major Ethiopian cities</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Lead the industry in technology and innovation</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-check-line text-green-600 mt-1 text-lg"></i>
                        <span class="text-gray-700">Create lasting value for communities we serve</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="section-label">Why Choose Us</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">
                What Sets Us Apart
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Experience the Addis Mark difference with our commitment to excellence
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="achievement-card" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ri-shield-check-line text-2xl text-blue-600"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Verified Properties</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Every listing is personally verified by our team for accuracy and quality.</p>
            </div>
            
            <div class="achievement-card" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ri-team-line text-2xl text-green-600"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Expert Agents</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Our agents are trained professionals with deep local market knowledge.</p>
            </div>
            
            <div class="achievement-card" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ri-customer-service-2-line text-2xl text-purple-600"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Round-the-clock assistance for all your real estate inquiries and needs.</p>
            </div>
            
            <div class="achievement-card" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ri-secure-payment-line text-2xl text-amber-600"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Secure Transactions</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Safe and transparent processes for worry-free property transactions.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Journey / Timeline -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="section-label">Our Journey</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">
                Milestones of Excellence
            </h2>
        </div>
        
        <div class="max-w-3xl mx-auto">
            <div class="timeline-item" data-aos="fade-up">
                <span class="text-sm font-semibold text-blue-600">2010</span>
                <h4 class="text-xl font-bold text-gray-900 mt-1 mb-2">Company Founded</h4>
                <p class="text-gray-700">Addis Mark Real Estate was established with a vision to transform Ethiopia's real estate market.</p>
            </div>
            
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="50">
                <span class="text-sm font-semibold text-blue-600">2013</span>
                <h4 class="text-xl font-bold text-gray-900 mt-1 mb-2">First 100 Properties</h4>
                <p class="text-gray-700">Successfully listed and sold our first 100 properties, establishing trust in the community.</p>
            </div>
            
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                <span class="text-sm font-semibold text-blue-600">2016</span>
                <h4 class="text-xl font-bold text-gray-900 mt-1 mb-2">Expanded to Commercial</h4>
                <p class="text-gray-700">Launched our commercial real estate division, serving businesses across Addis Ababa.</p>
            </div>
            
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="150">
                <span class="text-sm font-semibold text-blue-600">2019</span>
                <h4 class="text-xl font-bold text-gray-900 mt-1 mb-2">Digital Transformation</h4>
                <p class="text-gray-700">Launched our online platform, making property search easier and more accessible.</p>
            </div>
            
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                <span class="text-sm font-semibold text-blue-600">2023</span>
                <h4 class="text-xl font-bold text-gray-900 mt-1 mb-2">1,000+ Clients Served</h4>
                <p class="text-gray-700">Celebrated serving over 1,000 happy clients across Ethiopia.</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="section-label">Our Values</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">
                The Principles That Guide Us
            </h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i class="ri-heart-line text-4xl text-blue-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3">Integrity</h4>
                <p class="text-gray-600">We operate with honesty and transparency in every interaction.</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i class="ri-star-line text-4xl text-green-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3">Excellence</h4>
                <p class="text-gray-600">We strive for the highest standards in everything we do.</p>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i class="ri-group-line text-4xl text-purple-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3">Client First</h4>
                <p class="text-gray-600">Your success and satisfaction are our top priorities.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="container mx-auto px-4 text-center">
        <div data-aos="zoom-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                Ready to Work With Ethiopia's Best?
            </h2>
            <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
                Let our expert team help you find your perfect property or sell your current one.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('properties.index') }}" class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-xl hover:bg-gray-100 transition shadow-lg">
                    Browse Properties
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-xl hover:bg-white/10 transition">
                    Contact Our Team
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