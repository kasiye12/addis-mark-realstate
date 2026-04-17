<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Dynamic Title --}}
    <title>@yield('title', setting('site_name', 'Addis Mark Real Estate')) | Premium Properties in Ethiopia</title>
    
    {{-- Meta Tags --}}
    <meta name="description" content="@yield('meta_description', setting('site_description', 'Find your dream property in Addis Ababa, Ethiopia. Luxury homes, apartments, commercial spaces for sale and rent.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'real estate, Ethiopia, Addis Ababa, properties, homes, apartments, luxury homes, commercial, land')">
    <meta name="author" content="{{ setting('site_name', 'Addis Mark Real Estate') }}">
    <meta name="robots" content="index, follow">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', setting('site_name', 'Addis Mark Real Estate'))">
    <meta property="og:description" content="@yield('meta_description', setting('site_description', 'Premium Real Estate in Ethiopia'))">
    <meta property="og:image" content="@yield('meta_image', setting('site_logo') ? route('file.show', ['path' => setting('site_logo')]) : asset('images/og-image.jpg'))">
    <meta property="og:site_name" content="{{ setting('site_name', 'Addis Mark Real Estate') }}">
    <meta property="og:locale" content="en_US">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', setting('site_name', 'Addis Mark Real Estate'))">
    <meta name="twitter:description" content="@yield('meta_description', setting('site_description', 'Premium Real Estate in Ethiopia'))">
    <meta name="twitter:image" content="@yield('meta_image', setting('site_logo') ? route('file.show', ['path' => setting('site_logo')]) : asset('images/og-image.jpg'))">
    
    {{-- Favicon from Settings --}}
    @php
        $faviconPath = \App\Models\Setting::get('site_favicon');
        $faviconUrl = $faviconPath ? route('file.show', ['path' => $faviconPath]) : asset('favicon.ico');
        $themeColor = '#2563eb';
    @endphp
    
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $faviconUrl }}">
    
    {{-- Theme Color for Mobile Browsers --}}
    <meta name="theme-color" content="{{ $themeColor }}">
    <meta name="msapplication-TileColor" content="{{ $themeColor }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Preconnect for Performance --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    {{-- Fonts --}}
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    {{-- AOS Animation --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    
    {{-- Remix Icon --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Custom Styles --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    
    {{-- Navbar --}}
    @include('frontend.partials.navbar')
    
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('frontend.partials.footer')
    
    {{-- Back to Top Button --}}
    <button id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-6 right-6 w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-blue-700 z-50 flex items-center justify-center">
        <i class="ri-arrow-up-line text-xl"></i>
    </button>
    
    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });
        
        // Back to Top Button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#0') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Lazy load images
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    </script>
    
    {{-- Custom Scripts --}}
    @stack('scripts')
</body>
</html>