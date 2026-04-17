@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Website Settings</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="ri-check-line mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="ri-error-warning-line mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @php
        // Get settings with fallbacks
        $siteName = \App\Models\Setting::get('site_name', 'Addis Mark Real Estate');
        $siteDescription = \App\Models\Setting::get('site_description', '');
        $contactEmail = \App\Models\Setting::get('contact_email', 'info@addismark.com');
        $contactPhone = \App\Models\Setting::get('contact_phone', '+251 11 234 5678');
        $contactAddress = \App\Models\Setting::get('contact_address', '');
        $facebookUrl = \App\Models\Setting::get('facebook_url', '');
        $instagramUrl = \App\Models\Setting::get('instagram_url', '');
        $twitterUrl = \App\Models\Setting::get('twitter_url', '');
        $linkedinUrl = \App\Models\Setting::get('linkedin_url', '');
        $siteLogo = \App\Models\Setting::get('site_logo');
        $siteFavicon = \App\Models\Setting::get('site_favicon');
        $homepageVideo = \App\Models\Setting::get('homepage_video');
        $homepageVideoPoster = \App\Models\Setting::get('homepage_video_poster');
        
        // Check if files exist
        $logoExists = $siteLogo && \Storage::disk('public')->exists($siteLogo);
        $faviconExists = $siteFavicon && \Storage::disk('public')->exists($siteFavicon);
        $videoExists = $homepageVideo && \Storage::disk('public')->exists($homepageVideo);
        $posterExists = $homepageVideoPoster && \Storage::disk('public')->exists($homepageVideoPoster);
    @endphp

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-settings-4-line text-xl text-blue-600"></i>
                <h2 class="text-lg font-semibold">General Settings</h2>
            </div>
            
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name *</label>
                        <input type="text" name="site_name" value="{{ $siteName }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_name') border-red-300 @enderror">
                        @error('site_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                        <textarea name="site_description" rows="2" 
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $siteDescription }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ $contactEmail }}" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ $contactPhone }}" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="contact_address" rows="2" 
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $contactAddress }}</textarea>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="ri-save-line"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-share-line text-xl text-blue-600"></i>
                <h2 class="text-lg font-semibold">Social Media</h2>
            </div>
            
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="ri-facebook-fill text-blue-600 mr-1"></i> Facebook
                        </label>
                        <input type="url" name="facebook_url" value="{{ $facebookUrl }}" placeholder="https://facebook.com/yourpage"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="ri-instagram-fill text-pink-600 mr-1"></i> Instagram
                        </label>
                        <input type="url" name="instagram_url" value="{{ $instagramUrl }}" placeholder="https://instagram.com/yourpage"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="ri-twitter-x-fill text-gray-800 mr-1"></i> Twitter/X
                        </label>
                        <input type="url" name="twitter_url" value="{{ $twitterUrl }}" placeholder="https://twitter.com/yourpage"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="ri-linkedin-fill text-blue-700 mr-1"></i> LinkedIn
                        </label>
                        <input type="url" name="linkedin_url" value="{{ $linkedinUrl }}" placeholder="https://linkedin.com/company/yourpage"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mt-# Continuing the settings view...

                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="ri-save-line"></i> Save Social Links
                    </button>
                </div>
            </form>
        </div>

        <!-- Logo & Favicon -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-image-line text-xl text-blue-600"></i>
                <h2 class="text-lg font-semibold">Logo & Favicon</h2>
            </div>
            
            <div class="space-y-6">
                <!-- Logo Upload -->
                <div class="border-b border-gray-100 pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                    
                    @if($logoExists)
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $siteLogo) }}" 
                                 alt="Site Logo" 
                                 class="h-12 w-auto"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="h-12 bg-gray-100 rounded-lg items-center justify-center text-gray-400 text-sm" style="display: none;">
                                <i class="ri-image-line mr-1"></i> Image not found
                            </div>
                        </div>
                    @else
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                            <i class="ri-image-add-line text-3xl text-gray-400 mb-1"></i>
                            <p class="text-gray-500 text-sm">No logo uploaded</p>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.settings.upload-logo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-2">
                            <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg,image/gif,image/svg,image/webp" required
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                <i class="ri-upload-line mr-1"></i> Upload
                            </button>
                        </div>
                    </form>
                    
                    @if($logoExists)
                        <form action="{{ route('admin.settings.delete-logo') }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to delete the logo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm hover:text-red-800">
                                <i class="ri-delete-bin-line mr-1"></i> Remove Logo
                            </button>
                        </form>
                    @endif
                    
                    <p class="text-xs text-gray-500 mt-2">Recommended: PNG with transparent background, max 5MB</p>
                </div>
                
                <!-- Favicon Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    
                    @if($faviconExists)
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $siteFavicon) }}" 
                                 alt="Favicon" 
                                 class="h-8 w-auto">
                        </div>
                    @else
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                            <i class="ri-image-add-line text-2xl text-gray-400 mb-1"></i>
                            <p class="text-gray-500 text-sm">No favicon uploaded</p>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.settings.upload-favicon') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-2">
                            <input type="file" name="favicon" accept="image/png,image/jpeg,image/jpg,image/gif,image/x-icon,image/vnd.microsoft.icon" required
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                <i class="ri-upload-line mr-1"></i> Upload
                            </button>
                        </div>
                    </form>
                    <p class="text-xs text-gray-500 mt-2">Recommended: 32x32px or 16x16px, ICO/PNG format, max 1MB</p>
                </div>
            </div>
        </div>

        <!-- System Tools -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-tools-line text-xl text-blue-600"></i>
                <h2 class="text-lg font-semibold">System Tools</h2>
            </div>
            
            <div class="space-y-3">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" onsubmit="return confirm('Clear all application cache?')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 text-left border border-yellow-200 transition">
                        <div class="flex items-center">
                            <i class="ri-delete-back-line text-xl mr-3"></i>
                            <div>
                                <p class="font-medium">Clear Application Cache</p>
                                <p class="text-xs text-yellow-600">Refresh cached views, routes, and config</p>
                            </div>
                        </div>
                    </button>
                </form>
                
                <form action="{{ route('admin.settings.backup') }}" method="POST" onsubmit="return confirm('Create a new system backup?')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-left border border-green-200 transition">
                        <div class="flex items-center">
                            <i class="ri-database-2-line text-xl mr-3"></i>
                            <div>
                                <p class="font-medium">Create System Backup</p>
                                <p class="text-xs text-green-600">Backup database and important files</p>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Homepage Video Section -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ri-video-line text-xl text-blue-600"></i>
            <h2 class="text-lg font-semibold">Homepage Promotional Video</h2>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Video Player -->
            <div>
                @if($videoExists)
                    <div class="relative rounded-lg overflow-hidden bg-black">
                        <video controls class="w-full" poster="{{ $posterExists ? asset('storage/' . $homepageVideoPoster) : '' }}">
                            <source src="{{ asset('storage/' . $homepageVideo) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            <i class="ri-check-line text-green-500 mr-1"></i> Video uploaded
                        </span>
                        <form action="{{ route('admin.settings.delete-video') }}" method="POST" onsubmit="return confirm('Delete this video?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm hover:text-red-800">
                                <i class="ri-delete-bin-line mr-1"></i> Delete Video
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <i class="ri-video-upload-line text-5xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">No video uploaded</p>
                        <p class="text-sm text-gray-400 mt-1">Upload a promotional video for the homepage</p>
                    </div>
                @endif
            </div>
            
            <!-- Upload Form -->
            <div>
                <form action="{{ route('admin.settings.upload-video') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Video File (MP4)</label>
                            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Max size: 50MB. Supported: MP4, MOV, AVI</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Video Poster/Thumbnail</label>
                            <input type="file" name="poster" accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Image shown before video plays. Max 2MB</p>
                        </div>
                        
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i class="ri-upload-cloud-line"></i> Upload Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection