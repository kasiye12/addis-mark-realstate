<footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    @php
                        $logoPath = \App\Models\Setting::get('site_logo');
                        $siteName = \App\Models\Setting::get('site_name', 'Addis Mark Real Estate');
                        $logoUrl = $logoPath && \Storage::disk('public')->exists($logoPath) 
                            ? route('file.show', ['path' => $logoPath]) 
                            : null;
                    @endphp
                    
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="ri-building-line text-white text-xl"></i>
                        </div>
                    @endif
                    <span class="text-2xl font-bold">{{ $siteName }}</span>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ \App\Models\Setting::get('site_description', 'Your trusted partner in Ethiopian real estate. Find your dream property with us.') }}
                </p>
                <div class="flex gap-3">
                    @php
                        $facebook = \App\Models\Setting::get('facebook_url');
                        $instagram = \App\Models\Setting::get('instagram_url');
                        $twitter = \App\Models\Setting::get('twitter_url');
                        $linkedin = \App\Models\Setting::get('linkedin_url');
                    @endphp
                    
                    @if($facebook)
                    <a href="{{ $facebook }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-[#1877f2] transition-all hover:scale-110">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    @endif
                    
                    @if($twitter)
                    <a href="{{ $twitter }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-black transition-all hover:scale-110">
                        <i class="ri-twitter-x-fill"></i>
                    </a>
                    @endif
                    
                    @if($instagram)
                    <a href="{{ $instagram }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-[#e4405f] transition-all hover:scale-110">
                        <i class="ri-instagram-line"></i>
                    </a>
                    @endif
                    
                    @if($linkedin)
                    <a href="{{ $linkedin }}" target="_blank" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-[#0a66c2] transition-all hover:scale-110">
                        <i class="ri-linkedin-fill"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4 relative inline-block">
                    Quick Links
                    <span class="absolute -bottom-1 left-0 w-12 h-0.5 bg-blue-600"></span>
                </h3>
                <ul class="space-y-2 text-gray-400">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('properties.index') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">Properties</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projects.index') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">Blog</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">About Us</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-white transition flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-blue-500 opacity-0 group-hover:opacity-100 transition"></i>
                            <span class="group-hover:translate-x-1 transition-transform">Contact</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 relative inline-block">
                    Contact Info
                    <span class="absolute -bottom-1 left-0 w-12 h-0.5 bg-blue-600"></span>
                </h3>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center mt-0.5">
                            <i class="ri-map-pin-line text-blue-400"></i>
                        </div>
                        <span>{{ \App\Models\Setting::get('contact_address', 'Bole Road, Addis Ababa, Ethiopia') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center">
                            <i class="ri-phone-line text-blue-400"></i>
                        </div>
                        <a href="tel:{{ \App\Models\Setting::get('contact_phone', '+251112345678') }}" class="hover:text-white transition">
                            {{ \App\Models\Setting::get('contact_phone', '+251 11 234 5678') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center">
                            <i class="ri-mail-line text-blue-400"></i>
                        </div>
                        <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info@addismark.com') }}" class="hover:text-white transition">
                            {{ \App\Models\Setting::get('contact_email', 'info@addismark.com') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center">
                            <i class="ri-time-line text-blue-400"></i>
                        </div>
                        <span>Mon - Fri: 8:30 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4 relative inline-block">
                    Newsletter
                    <span class="absolute -bottom-1 left-0 w-12 h-0.5 bg-blue-600"></span>
                </h3>
                <p class="text-gray-400 mb-4 leading-relaxed">Subscribe to get updates on new properties and market insights.</p>
                
                @if(session('newsletter_success'))
                    <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-2 rounded-lg mb-3 text-sm">
                        <i class="ri-check-line mr-1"></i> {{ session('newsletter_success') }}
                    </div>
                @endif
                
                @if($errors->has('email') && request()->routeIs('newsletter.subscribe'))
                    <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-2 rounded-lg mb-3 text-sm">
                        <i class="ri-error-warning-line mr-1"></i> {{ $errors->first('email') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <div class="flex gap-2">
                        <input type="email" 
                               name="email"
                               placeholder="Your email address" 
                               required
                               class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-blue-500 text-white placeholder-gray-500">
                        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center justify-center">
                            <i class="ri-send-plane-fill"></i>
                        </button>
                    </div>
                </form>
                <p class="text-gray-500 text-xs mt-3">
                    <i class="ri-lock-line mr-1"></i> We respect your privacy. Unsubscribe anytime.
                </p>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-12 pt-8">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Addis Mark Real Estate') }}. All rights reserved.
                </p>
                
            </div>
        </div>
    </div>
</footer>