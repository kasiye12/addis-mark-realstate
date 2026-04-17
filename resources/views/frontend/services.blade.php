@extends('layouts.frontend')

@section('title', 'Our Services - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('meta_description', 'Comprehensive real estate services including property sales, rentals, property management, investment consulting, and more in Ethiopia.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    .service-card {
        background: white;
        border-radius: 24px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        border-color: #2563eb;
    }
    
    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .service-card:hover .service-icon {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    .service-card:hover .service-icon i {
        color: white !important;
    }
    
    .process-step {
        position: relative;
        text-align: center;
    }
    
    .process-number {
        width: 60px;
        height: 60px;
        background: #2563eb;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        margin: 0 auto 20px;
        position: relative;
        z-index: 2;
    }
    
    .process-line {
        position: absolute;
        top: 30px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #2563eb 0%, #93c5fd 100%);
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .process-line {
            display: none;
        }
    }
    
    .pricing-card {
        background: white;
        border-radius: 24px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .pricing-card.featured {
        border: 2px solid #2563eb;
        transform: scale(1.02);
    }
    
    .pricing-card.featured::before {
        content: 'Most Popular';
        position: absolute;
        top: 20px;
        right: -35px;
        background: #2563eb;
        color: white;
        padding: 5px 40px;
        font-size: 12px;
        font-weight: 600;
        transform: rotate(45deg);
    }
    
    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
    }
    
    .pricing-card.featured:hover {
        transform: scale(1.02) translateY(-8px);
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4" data-aos="fade-up">
            <i class="ri-service-line mr-2"></i>What We Offer
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Our Services
        </h1>
        <p class="text-xl text-white/90 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            Comprehensive real estate solutions tailored to your unique needs in Ethiopia.
        </p>
    </div>
</section>

<!-- Main Services -->
<section class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Our Expertise</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">
                Comprehensive Real Estate Services
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                From buying and selling to property management, we've got you covered.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Service 1: Property Sales -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="0">
                <div class="service-icon">
                    <i class="ri-home-4-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Property Sales</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Expert guidance in buying and selling residential and commercial properties. We ensure you get the best value for your investment.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Free property valuation</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Market analysis & pricing strategy</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Professional photography & marketing</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Negotiation & closing support</span>
                    </li>
                </ul>
            </div>
            
            <!-- Service 2: Property Rentals -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="ri-key-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Property Rentals</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Find the perfect rental property with our extensive portfolio and expertise. We match tenants with their ideal homes.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Extensive rental listings</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Tenant screening & verification</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Lease agreement preparation</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Move-in/move-out inspections</span>
                    </li>
                </ul>
            </div>
            
            <!-- Service 3: Property Management -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="ri-building-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Property Management</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Full-service property management for landlords and investors. We handle everything so you can enjoy passive income.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Tenant placement & screening</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Rent collection & financial reporting</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Maintenance & repairs coordination</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Legal compliance & eviction handling</span>
                    </li>
                </ul>
            </div>
            
            <!-- Service 4: Investment Consulting -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="0">
                <div class="service-icon">
                    <i class="ri-money-dollar-circle-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Investment Consulting</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Strategic advice for real estate investors looking to maximize returns in Ethiopia's growing market.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Market research & opportunity analysis</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>ROI calculations & projections</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Portfolio diversification strategies</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Due diligence & risk assessment</span>
                    </li>
                </ul>
            </div>
            
            <!-- Service 5: Commercial Real Estate -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="ri-store-2-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Commercial Real Estate</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Specialized services for office spaces, retail locations, warehouses, and industrial properties.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Site selection & analysis</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Lease negotiation & renewal</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Market comparable analysis</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Tenant representation</span>
                    </li>
                </ul>
            </div>
            
            <!-- Service 6: Legal & Documentation -->
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="ri-scales-3-line text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Legal & Documentation</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Professional assistance with all legal aspects of property transactions in Ethiopia.
                </p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Contract review & preparation</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Title search & verification</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Registration & transfer assistance</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-green-600"></i>
                        <span>Tax consultation</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Our Process -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">How We Work</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2 mb-4">
                Our Simple Process
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                We make real estate transactions smooth and stress-free.
            </p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-8 max-w-5xl mx-auto">
            <div class="process-step" data-aos="fade-up" data-aos-delay="0">
                <div class="process-number">1</div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Consultation</h4>
                <p class="text-gray-600 text-sm">We discuss your needs, preferences, and goals to understand your requirements.</p>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="100">
                <div class="process-number">2</div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Search & Select</h4>
                <p class="text-gray-600 text-sm">We curate the best options and arrange viewings of suitable properties.</p>
                <div class="process-line"></div>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="200">
                <div class="process-number">3</div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Negotiation</h4>
                <p class="text-gray-600 text-sm">We negotiate the best terms and price on your behalf.</p>
                <div class="process-line"></div>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="300">
                <div class="process-number">4</div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Closing</h4>
                <p class="text-gray-600 text-sm">We handle all paperwork and ensure a smooth transaction closing.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="container mx-auto px-4 text-center">
        <div data-aos="zoom-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                Ready to Get Started?
            </h2>
            <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
                Contact us today to discuss your real estate needs with our expert team.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg">
                    Contact Us
                </a>
                <a href="{{ route('properties.index') }}" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition">
                    Browse Properties
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