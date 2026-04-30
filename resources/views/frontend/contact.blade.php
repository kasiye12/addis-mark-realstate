@extends('layouts.frontend')

@section('title', 'Contact Us - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('meta_description', 'Contact Addis Mark Real Estate - Ethiopia\'s premier property development company. Visit our office near Welo-Sefer Roundabout or call us for premium properties.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    .contact-card:hover {
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
        border-color: #2563eb;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-icon {
        width: 48px;
        height: 48px;
        background: #eff6ff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 22px;
        transition: all 0.3s ease;
    }
    .info-item:hover .info-icon {
        background: #2563eb;
        color: white;
        transform: scale(1.05);
    }
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
        height: 100%;
        min-height: 300px;
    }
    .map-container iframe {
        width: 100%;
        height: 100%;
        min-height: 350px;
    }
    .social-link {
        width: 40px;
        height: 40px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .social-link:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-3px);
    }
    .form-input {
        width: 100%;
        padding: 14px 18px;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        font-size: 15px;
        transition: all 0.2s ease;
        background: white;
    }
    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 8px;
    }
    .submit-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.3);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -5px rgba(37, 99, 235, 0.4);
    }
    
    /* Section Label */
    .section-label {
        color: #2563eb;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }
    
    /* Hero Section - REDUCED SIZE like About page */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endpush

@section('content')

<!-- Hero Section - REDUCED SIZE (py-12 lg:py-16 like About page) -->
<section class="relative bg-blue-600 py-12 lg:py-16 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4 animate-float" data-aos="fade-up" data-aos-duration="800">
            <i class="ri-customer-service-2-line mr-1"></i>
            <span>Mark of Reliability & Excellence</span>
        </span>
        
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            Get in Touch
        </h1>
        
        <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
            Have questions about our premium properties or services? We're here to help you find your dream home.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Contact Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Office Location Card -->
                <div class="contact-card" data-aos="fade-up" data-aos-duration="700">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-map-pin-line text-blue-600"></i>
                        Office Location
                    </h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="ri-building-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Addis Mark Real Estate</p>
                            <p class="text-gray-600 text-sm mt-1">Welo-Sefer Roundabout<br>250 Meters into Ethio-China Street</p>
                            <p class="text-gray-600 text-sm mt-1">Addis Ababa, Ethiopia</p>
                        </div>
                    </div>
                    
                    <!-- Showroom Locations -->
                    <div class="mt-4 pt-2">
                        <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="ri-store-3-line text-blue-600"></i>
                            Our Locations
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 group cursor-pointer">
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 group-hover:scale-150 transition"></div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">Addis Mark Welo-Sefer</p>
                                    <p class="text-gray-500 text-xs">Near Welo Sefer Roundabout</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 group cursor-pointer">
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 group-hover:scale-150 transition"></div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">Addis Mark Gerji to Bole</p>
                                    <p class="text-gray-500 text-xs">Gerji to Bole Homes Road</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mt-4">
                        <div class="info-icon">
                            <i class="ri-time-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Business Hours</p>
                            <p class="text-gray-600 text-sm mt-1">Monday - Friday: 8:30 AM - 6:00 PM</p>
                            <p class="text-gray-600 text-sm">Saturday: 9:00 AM - 4:00 PM</p>
                            <p class="text-gray-600 text-sm">Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                
                <!-- Phone & Email Card -->
                <div class="contact-card" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-phone-line text-blue-600"></i>
                        Contact Info
                    </h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="ri-phone-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Call Us</p>
                            <a href="tel:+251910413899" class="text-gray-600 text-sm mt-1 block hover:text-blue-600 transition">
                                +251 91 041 3899
                            </a>
                            <!--<a href="tel:+251112345678" class="text-gray-600 text-sm block hover:text-blue-600 transition">-->
                            <!--    +251 11 234 5678-->
                            <!--</a>-->
                            <p class="text-gray-400 text-xs mt-2">Available during business hours</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="ri-mail-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Email Us</p>
                            <a href="mailto:info@addismarkrealestate.et" class="text-gray-600 text-sm mt-1 block hover:text-blue-600 transition">
                                info@addismarkrealestate.et
                            </a>
                            <a href="mailto:sales@addismarkrealestate.et" class="text-gray-600 text-sm block hover:text-blue-600 transition">
                                sales@addismarkrealestate.et
                            </a>
                            <p class="text-gray-400 text-xs mt-2">We'll respond within 24 hours</p>
                        </div>
                    </div>
                    
                    <!-- Website -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="ri-global-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Website</p>
                            <a href="https://www.addismarkrealestate.et" target="_blank" class="text-gray-600 text-sm mt-1 block hover:text-blue-600 transition">
                                www.addismarkrealestate.et
                            </a>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="pt-4 mt-2">
                        <p class="text-sm font-medium text-gray-700 mb-3">Follow Us</p>
                        <div class="flex gap-3">
                            <a href="{{ setting('facebook_url', 'https://facebook.com/addismarkrealestate') }}" class="social-link" target="_blank">
                                <i class="ri-facebook-fill text-xl"></i>
                            </a>
                            <a href="{{ setting('instagram_url', 'https://instagram.com/addismarkrealestate') }}" class="social-link" target="_blank">
                                <i class="ri-instagram-line text-xl"></i>
                            </a>
                            <a href="{{ setting('twitter_url', '#') }}" class="social-link" target="_blank">
                                <i class="ri-twitter-x-fill text-xl"></i>
                            </a>
                            <a href="{{ setting('linkedin_url', '#') }}" class="social-link" target="_blank">
                                <i class="ri-linkedin-fill text-xl"></i>
                            </a>
                            <a href="{{ setting('telegram_url', '#') }}" class="social-link" target="_blank">
                                <i class="ri-telegram-line text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="contact-card" data-aos="fade-up" data-aos-duration="700" data-aos-delay="150">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-information-line text-blue-600"></i>
                        Quick Info
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Years of Experience</span>
                            <span class="font-bold text-blue-600">36+ Years</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total Units</span>
                            <span class="font-bold text-blue-600">1,825+</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Active Projects</span>
                            <span class="font-bold text-blue-600">2</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Sister Company</span>
                            <span class="font-bold text-blue-600">Alemayehu Ketema GC</span>
                        </div>
                    </div>
                    
                    <div class="mt-5 pt-3 border-t border-gray-100">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-blue-700 font-medium flex items-center justify-center gap-1">
                                <i class="ri-shield-check-line"></i>
                                "Mark of Reliability & Excellence"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Contact Form -->
            <div class="lg:col-span-2">
                <div class="contact-card" id="contact-form" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ri-message-2-line text-blue-600"></i>
                        Send Us a Message
                    </h3>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                            <i class="ri-checkbox-circle-fill text-xl"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6">
                            <p class="font-medium mb-1">Please correct the following errors:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        
                        {{-- Hidden field to specify recipient email --}}
                        <input type="hidden" name="recipient_email" value="info@addismarkrealestate.et">
                        
                        <div class="grid md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="form-input" placeholder="Enter your full name">
                            </div>
                            <div>
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="form-input" placeholder="your@email.com">
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       class="form-input" placeholder="+251 XX XXX XXXX">
                            </div>
                            <div>
                                <label class="form-label">Subject *</label>
                                <select name="subject" required class="form-input">
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="Property Viewing" {{ old('subject') == 'Property Viewing' ? 'selected' : '' }}>Property Viewing</option>
                                    <option value="Property Purchase" {{ old('subject') == 'Property Purchase' ? 'selected' : '' }}>Property Purchase</option>
                                    <option value="Commercial Space" {{ old('subject') == 'Commercial Space' ? 'selected' : '' }}>Commercial Space</option>
                                    <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Career" {{ old('subject') == 'Career' ? 'selected' : '' }}>Career Opportunities</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="form-label">Message *</label>
                            <textarea name="message" rows="6" required
                                      class="form-input" placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn group">
                            <i class="ri-send-plane-fill mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Send Message
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center mt-4 flex items-center justify-center gap-1">
                            <i class="ri-time-line"></i>
                            We'll get back to you within 24 hours
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="pb-16 lg:pb-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="section-label text-blue-600 text-sm uppercase tracking-wide font-semibold">Find Us</span>
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">Visit Our Office</h2>
            <p class="text-gray-600 mt-2">📍 Welo-Sefer Roundabout, 250 Meters into Ethio-China Street</p>
        </div>
        <div class="map-container" data-aos="fade-up" data-aos-duration="800">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!4v1776366244046!6m8!1m7!1sCAoSHENJQUJJaENLREw4NTk3MG1keG51TWdyZUpqYWY.!2m2!1d8.992857223416536!2d38.77394489156825!3f112.15688!4f0!5f0.7820865974627469" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <!-- Direction Note -->
        <div class="text-center mt-6" data-aos="fade-up" data-aos-delay="100">
            <p class="text-gray-500 text-sm flex items-center justify-center gap-1">
                <i class="ri-map-pin-line text-blue-600"></i> 
                From Welo-Sefer Roundabout, head 250 meters into Ethio-China Street. Our office is located on the right side.
            </p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="container mx-auto px-4 text-center">
        <div data-aos="zoom-in" data-aos-duration="800">
            <h2 class="text-2xl lg:text-3xl font-bold text-white mb-3">
                Ready to Own Your Dream Home?
            </h2>
            <p class="text-white/90 text-base mb-6 max-w-2xl mx-auto">
                Browse our premium properties in prime locations across Addis Ababa. Experience the mark of reliability and excellence.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('properties.index') }}" class="px-6 py-2.5 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg inline-flex items-center gap-2">
                    <i class="ri-building-line"></i>
                    Browse Properties
                </a>
                <a href="{{ route('about') }}" class="px-6 py-2.5 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition inline-flex items-center gap-2">
                    <i class="ri-information-line"></i>
                    Learn About Us
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
        AOS.init({ 
            duration: 800, 
            once: true, 
            offset: 50,
            easing: 'ease-out-cubic'
        });
    });
</script>
@endpush