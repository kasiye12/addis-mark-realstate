<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ setting('site_name', 'Addis Mark Real Estate') }}</title>
    
    {{-- Favicon --}}
    @php
        $faviconPath = \App\Models\Setting::get('site_favicon');
        $faviconUrl = $faviconPath ? route('file.show', ['path' => $faviconPath]) : asset('favicon.ico');
        $siteLogo = \App\Models\Setting::get('site_logo');
        $logoUrl = $siteLogo ? route('file.show', ['path' => $siteLogo]) : null;
        
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        $isAgent = $user->isAgent();
    @endphp
    
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <meta name="theme-color" content="#2563eb">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <!-- Logo Area -->
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-8 w-auto brightness-0 invert">
                    @else
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-building-line text-white"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-lg font-bold leading-tight">Addis Mark</h1>
                        <p class="text-xs text-gray-400">
                            {{ $isAdmin ? 'Admin Panel' : ($isAgent ? 'Agent Panel' : 'Dashboard') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                
                {{-- Dashboard - Everyone --}}
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span>Dashboard</span>
                    </a>
                @elseif($isAgent)
                    <a href="{{ route('agent.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-dashboard-line text-xl"></i>
                        <span>Dashboard</span>
                    </a>
                @endif
                
                {{-- Management Section --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>
                
                {{-- Properties - Admin sees all, Agent sees their own --}}
                @if($isAdmin)
                    <a href="{{ route('admin.properties.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.properties.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-building-line text-xl"></i>
                        <span>All Properties</span>
                    </a>
                @elseif($isAgent)
                    <a href="{{ route('agent.properties.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.properties.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-building-line text-xl"></i>
                        <span>My Properties</span>
                    </a>
                @endif
                
                {{-- Admin Only Menu Items --}}
                @if($isAdmin)
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-folder-line text-xl"></i>
                        <span>Categories</span>
                    </a>
                    
                    <a href="{{ route('admin.locations.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.locations.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-map-pin-line text-xl"></i>
                        <span>Locations</span>
                    </a>
                    {{-- Projects --}}
<a href="{{ route('admin.projects.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
    <i class="ri-building-2-line text-xl"></i>
    <span>Projects</span>
</a>

{{-- Blog --}}
<a href="{{ route('admin.blog.posts.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.blog.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
    <i class="ri-article-line text-xl"></i>
    <span>Blog</span>
</a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-user-line text-xl"></i>
                        <span>Users</span>
                    </a>
                    
                    <a href="{{ route('admin.testimonials.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.testimonials.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-chat-quote-line text-xl"></i>
                        <span>Testimonials</span>
                    </a>
                    
                    <a href="{{ route('admin.team.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.team.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-team-line text-xl"></i>
                        <span>Team</span>
                    </a>
                    
                    {{-- System Section - Admin Only --}}
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                    </div>
                    
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-settings-line text-xl"></i>
                        <span>Settings</span>
                    </a>
                    
                    <a href="{{ route('admin.analytics') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.analytics') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                        <i class="ri-bar-chart-2-line text-xl"></i>
                        <span>Analytics</span>
                    </a>
                @endif
            </nav>
            
            {{-- User Footer --}}
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($user->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ $user->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-400">{{ $isAdmin ? 'Administrator' : ($isAgent ? 'Agent' : 'User') }}</p>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-gray-300 hover:text-white text-sm mb-2 transition">
                    <i class="ri-external-link-line"></i> View Website
                </a>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-gray-300 hover:text-white text-sm mb-2 transition">
                    <i class="ri-user-settings-line"></i> Profile Settings
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-red-400 hover:text-red-300 text-sm w-full transition">
                        <i class="ri-logout-box-line"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>