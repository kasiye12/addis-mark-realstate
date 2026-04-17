<nav x-data="{ mobileMenuOpen: false, scrolled: false, userMenuOpen: false }" 
     @scroll.window="scrolled = window.pageYOffset > 10"
     :class="{ 'shadow-lg': scrolled }"
     class="bg-white border-b border-gray-200 sticky top-0 z-50 transition-all duration-300">
    
    <div class="container mx-auto px-4 lg:px-6">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                @php
                    $logoPath = \App\Models\Setting::get('site_logo');
                    $siteName = \App\Models\Setting::get('site_name', 'Addis Mark');
                    $logoUrl = $logoPath && \Storage::disk('public')->exists($logoPath) 
                        ? route('file.show', ['path' => $logoPath]) 
                        : null;
                @endphp
                
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto transition-transform group-hover:scale-105">
                @else
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition">
                        <i class="ri-building-line text-white text-sm"></i>
                    </div>
                @endif
                <span class="text-lg font-bold text-gray-900">{{ $siteName }}</span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Home
                    @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('properties.index') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('properties.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Properties
                    @if(request()->routeIs('properties.*'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
                
                {{-- Projects Link --}}
                <a href="{{ route('projects.index') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('projects.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Projects
                    @if(request()->routeIs('projects.*'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
                
                {{-- Blog Link --}}
                <a href="{{ route('blog.index') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('blog.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Blog
                    @if(request()->routeIs('blog.*'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
                
                <a href="{{ route('about') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50' : '' }}">
                    About
                    @if(request()->routeIs('about'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('contact') }}" 
                   class="relative px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition font-medium {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Contact
                    @if(request()->routeIs('contact'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-blue-600 rounded-full"></span>
                    @endif
                </a>
            </div>
            
            <!-- Desktop Right Menu -->
            <div class="hidden lg:flex items-center gap-2">
                @auth
                    @php
                        $user = auth()->user();
                        $isAdmin = $user->isAdmin();
                        $isAgent = $user->isAgent();
                    @endphp
                    
                    {{-- Admin Button --}}
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-sm">
                            <i class="ri-admin-line"></i>
                            <span>Admin</span>
                        </a>
                    @endif
                    
                    {{-- Agent Button --}}
                    @if($isAgent && !$isAdmin)
                        <a href="{{ route('agent.dashboard') }}" 
                           class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-sm">
                            <i class="ri-user-star-line"></i>
                            <span>Agent</span>
                        </a>
                    @endif
                    
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition border border-transparent hover:border-gray-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-semibold shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium max-w-[120px] truncate">{{ $user->name }}</span>
                            <i class="ri-arrow-down-s-line text-gray-400 transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-2 overflow-hidden z-50">
                            
                            {{-- User Info Header --}}
                            <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 text-xs font-medium rounded-full {{ $user->role_badge[0] }}">
                                    {{ $user->role_badge[1] }}
                                </span>
                            </div>
                            
                            {{-- Dashboard Link --}}
                            @if($isAdmin)
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <i class="ri-dashboard-line text-lg"></i>
                                    <span class="font-medium">Admin Dashboard</span>
                                </a>
                            @elseif($isAgent)
                                <a href="{{ route('agent.dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                    <i class="ri-dashboard-line text-lg"></i>
                                    <span class="font-medium">Agent Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                                    <i class="ri-dashboard-line text-lg"></i>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            @endif
                            
                            {{-- Profile Settings --}}
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                                <i class="ri-user-settings-line text-lg"></i>
                                <span class="font-medium">Profile Settings</span>
                            </a>
                            
                            {{-- My Properties (Agents and Admins) --}}
                            @if($isAdmin || $isAgent)
                                <a href="{{ route('user.properties.index') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                                    <i class="ri-building-line text-lg"></i>
                                    <span class="font-medium">My Properties</span>
                                </a>
                            @endif
                            
                            {{-- Saved Properties --}}
                            <a href="{{ route('user.properties.favorites') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                                <i class="ri-heart-line text-lg"></i>
                                <span class="font-medium">Saved Properties</span>
                            </a>
                            
                            <hr class="my-1 border-gray-100">
                            
                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="ri-logout-box-line text-lg"></i>
                                    <span class="font-medium">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition text-sm font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium shadow-sm hover:shadow">
                        Sign Up
                    </a>
                @endauth
            </div>
            
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="lg:hidden p-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-menu-line text-xl" x-show="!mobileMenuOpen"></i>
                <i class="ri-close-line text-xl" x-show="mobileMenuOpen"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         @click.away="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-t border-gray-200 shadow-lg">
        <div class="container mx-auto px-4 py-4 space-y-1 max-h-[calc(100vh-4rem)] overflow-y-auto">
            
            {{-- Main Navigation --}}
            <a href="{{ route('home') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-home-4-line text-xl"></i>
                <span class="font-medium">Home</span>
            </a>
            
            <a href="{{ route('properties.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('properties.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-building-line text-xl"></i>
                <span class="font-medium">Properties</span>
            </a>
            
            {{-- Projects Mobile Link --}}
            <a href="{{ route('projects.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('projects.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-building-2-line text-xl"></i>
                <span class="font-medium">Projects</span>
            </a>
            
            {{-- Blog Mobile Link --}}
            <a href="{{ route('blog.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('blog.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-article-line text-xl"></i>
                <span class="font-medium">Blog</span>
            </a>
            
            <a href="{{ route('about') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-information-line text-xl"></i>
                <span class="font-medium">About</span>
            </a>
            <a href="{{ route('contact') }}" 
               class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="ri-mail-line text-xl"></i>
                <span class="font-medium">Contact</span>
            </a>
            
            <hr class="my-4 border-gray-100">
            
            @auth
                @php
                    $user = auth()->user();
                    $isAdmin = $user->isAdmin();
                    $isAgent = $user->isAgent();
                @endphp
                
                {{-- User Info Card --}}
                <div class="flex items-center gap-4 px-4 py-3 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $user->role_badge[0] }}">
                            {{ $user->role_badge[1] }}
                        </span>
                    </div>
                </div>
                
                {{-- Dashboard Links --}}
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-purple-600 hover:bg-purple-50 rounded-xl transition">
                        <i class="ri-admin-line text-xl"></i>
                        <span class="font-medium">Admin Dashboard</span>
                    </a>
                @elseif($isAgent)
                    <a href="{{ route('agent.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-blue-600 hover:bg-blue-50 rounded-xl transition">
                        <i class="ri-user-star-line text-xl"></i>
                        <span class="font-medium">Agent Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                @endif
                
                {{-- Profile Settings --}}
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                    <i class="ri-user-settings-line text-xl"></i>
                    <span class="font-medium">Profile Settings</span>
                </a>
                
                {{-- My Properties --}}
                @if($isAdmin || $isAgent)
                    <a href="{{ route('user.properties.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                        <i class="ri-building-line text-xl"></i>
                        <span class="font-medium">My Properties</span>
                    </a>
                @endif
                
                {{-- Saved Properties --}}
                <a href="{{ route('user.properties.favorites') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                    <i class="ri-heart-line text-xl"></i>
                    <span class="font-medium">Saved Properties</span>
                </a>
                
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition">
                        <i class="ri-logout-box-line text-xl"></i>
                        <span class="font-medium">Sign Out</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition">
                    <i class="ri-login-box-line text-xl"></i>
                    <span class="font-medium">Sign In</span>
                </a>
                <a href="{{ route('register') }}" 
                   class="flex items-center justify-center gap-2 px-4 py-3 mt-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <i class="ri-user-add-line text-xl"></i>
                    <span>Create Account</span>
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-16"></div>