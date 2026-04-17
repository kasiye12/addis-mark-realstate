<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $siteLogo = Setting::get('site_logo');
        $siteFavicon = Setting::get('site_favicon');
        
        return view('admin.settings.index', compact('siteLogo', 'siteFavicon'));
    }

    /**
     * Update text settings.
     */
    public function update(Request $request)
    {
        $fields = [
            'site_name', 'site_description', 'contact_email', 
            'contact_phone', 'contact_address', 'facebook_url',
            'instagram_url', 'twitter_url', 'linkedin_url'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Upload logo.
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        try {
            // Delete old logo
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('site_logo', $path, 'image');

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Logo uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to upload logo: ' . $e->getMessage());
        }
    }

    /**
     * Upload favicon.
     */
    public function uploadFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,ico,webp|max:1024',
        ]);

        try {
            // Delete old favicon
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            // Store new favicon
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path, 'image');

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Favicon uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to upload favicon: ' . $e->getMessage());
        }
    }

    /**
     * Delete logo.
     */
    public function deleteLogo()
    {
        try {
            $logoPath = Setting::get('site_logo');
            
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
                Log::info('Logo deleted: ' . $logoPath);
            }
            
            Setting::set('site_logo', null);

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Logo deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Logo deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to delete logo: ' . $e->getMessage());
        }
    }

    /**
     * Upload homepage video.
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv,webm|max:51200',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // Delete old video
            $oldVideo = Setting::get('homepage_video');
            if ($oldVideo && Storage::disk('public')->exists($oldVideo)) {
                Storage::disk('public')->delete($oldVideo);
            }

            // Store new video
            $videoPath = $request->file('video')->store('videos', 'public');
            Setting::set('homepage_video', $videoPath, 'video');

            // Upload poster if provided
            if ($request->hasFile('poster')) {
                $oldPoster = Setting::get('homepage_video_poster');
                if ($oldPoster && Storage::disk('public')->exists($oldPoster)) {
                    Storage::disk('public')->delete($oldPoster);
                }
                
                $posterPath = $request->file('poster')->store('settings', 'public');
                Setting::set('homepage_video_poster', $posterPath, 'image');
            }

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Video uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to upload video: ' . $e->getMessage());
        }
    }

    /**
     * Delete homepage video.
     */
    public function deleteVideo()
    {
        try {
            $videoPath = Setting::get('homepage_video');
            if ($videoPath && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }
            Setting::set('homepage_video', null);

            $posterPath = Setting::get('homepage_video_poster');
            if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }
            Setting::set('homepage_video_poster', null);

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Video deleted successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Failed to delete video: ' . $e->getMessage());
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        Artisan::call('optimize:clear');
        
        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Cache cleared successfully!');
    }

    /**
     * Create backup.
     */
    public function createBackup()
    {
        if (class_exists('\Spatie\Backup\Commands\BackupCommand')) {
            Artisan::call('backup:run');
            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Backup created successfully!');
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('info', 'Backup package not installed.');
    }
}