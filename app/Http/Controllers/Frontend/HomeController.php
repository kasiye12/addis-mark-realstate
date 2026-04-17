<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        // Featured Properties
        $featuredProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // Latest Properties
        $latestProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Properties for Sale
        $saleProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('price_type', 'sale')
            ->latest()
            ->take(3)
            ->get();

        // Properties for Rent
        $rentProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('price_type', 'rent')
            ->latest()
            ->take(3)
            ->get();

        // Popular Locations
        $popularLocations = Location::withCount(['properties' => function($query) {
            $query->where('is_active', true);
        }])
        ->where('is_popular', true)
        ->orderBy('properties_count', 'desc')
        ->take(8)
        ->get();

        // Property Categories
        $categories = Category::withCount(['properties' => function($query) {
            $query->where('is_active', true);
        }])
        ->where('is_active', true)
        ->orderBy('properties_count', 'desc')
        ->take(6)
        ->get();

        // Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Team Members
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        // Statistics
        $stats = [
            'properties' => Property::where('is_active', true)->count(),
            'locations' => Location::count(),
            'categories' => Category::where('is_active', true)->count(),
            'clients' => 100,
            'experience' => 05,
            
            'team_members' => TeamMember::where('is_active', true)->count(),
            'testimonials' => Testimonial::where('is_active', true)->count(),
        ];

        // Get Settings
        $siteLogo = Setting::get('site_logo');
        $siteFavicon = Setting::get('site_favicon');
        $siteName = Setting::get('site_name', 'Addis Mark Real Estate');
        $siteDescription = Setting::get('site_description', 'Premium Real Estate in Ethiopia');
        
        // Contact Settings
        $contactEmail = Setting::get('contact_email', 'info@addismark.com');
        $contactPhone = Setting::get('contact_phone', '+251 11 234 5678');
        $contactAddress = Setting::get('contact_address', 'Bole Road, Addis Ababa, Ethiopia');
        
        // Social Media Links
        $socialLinks = [
            'facebook' => Setting::get('facebook_url', '#'),
            'instagram' => Setting::get('instagram_url', '#'),
            'twitter' => Setting::get('twitter_url', '#'),
            'linkedin' => Setting::get('linkedin_url', '#'),
        ];

        // Video Settings
        $homepageVideo = Setting::get('homepage_video');
        $homepageVideoPoster = Setting::get('homepage_video_poster');
        
        // Build full URLs for video
        $videoUrl = null;
        $videoPosterUrl = null;
        
        if ($homepageVideo && Storage::disk('public')->exists($homepageVideo)) {
            $videoUrl = asset('storage/' . $homepageVideo);
        }
        
        if ($homepageVideoPoster && Storage::disk('public')->exists($homepageVideoPoster)) {
            $videoPosterUrl = asset('storage/' . $homepageVideoPoster);
        } else {
            $videoPosterUrl = asset('images/video-poster.jpg');
        }

        // Build logo URL
        $logoUrl = null;
        if ($siteLogo && Storage::disk('public')->exists($siteLogo)) {
            $logoUrl = asset('storage/' . $siteLogo);
        }

        // Build favicon URL
        $faviconUrl = null;
        if ($siteFavicon && Storage::disk('public')->exists($siteFavicon)) {
            $faviconUrl = asset('storage/' . $siteFavicon);
        }

        // Price ranges for filters
        $priceRanges = [
            'sale_min' => Property::where('is_active', true)->where('price_type', 'sale')->min('price') ?? 0,
            'sale_max' => Property::where('is_active', true)->where('price_type', 'sale')->max('price') ?? 50000000,
            'rent_min' => Property::where('is_active', true)->where('price_type', 'rent')->min('price') ?? 0,
            'rent_max' => Property::where('is_active', true)->where('price_type', 'rent')->max('price') ?? 100000,
        ];

        // Property types for filter
        $propertyTypes = [
            'apartment' => 'Apartment',
            'villa' => 'Villa',
            'commercial' => 'Commercial',
            'land' => 'Land',
            'office' => 'Office',
        ];

        // Bedroom options
        $bedroomOptions = [1, 2, 3, 4, 5];

        return view('frontend.home', compact(
            'featuredProperties',
            'latestProperties',
            'saleProperties',
            'rentProperties',
            'popularLocations',
            'categories',
            'testimonials',
            'teamMembers',
            'stats',
            'siteLogo',
            'siteName',
            'siteDescription',
            'contactEmail',
            'contactPhone',
            'contactAddress',
            'socialLinks',
            'homepageVideo',
            'homepageVideoPoster',
            'videoUrl',
            'videoPosterUrl',
            'logoUrl',
            'faviconUrl',
            'priceRanges',
            'propertyTypes',
            'bedroomOptions'
        ));
    }

    /**
     * Sitemap for SEO.
     */
    public function sitemap()
    {
        $properties = Property::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();
            
        $categories = Category::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get();
            
        $locations = Location::select('slug', 'updated_at')->get();

        return response()->view('sitemap', compact('properties', 'categories', 'locations'))
            ->header('Content-Type', 'text/xml');
    }

  

    /**
     * Testimonials page.
     */
    public function testimonials()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('frontend.testimonials', compact('testimonials'));
    }

    /**
     * Quick search for AJAX.
     */
    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $properties = Property::with(['category', 'location'])
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%")
                  ->orWhereHas('location', function($loc) use ($query) {
                      $loc->where('area_name', 'like', "%{$query}%")
                          ->orWhere('city', 'like', "%{$query}%");
                  });
            })
            ->limit(5)
            ->get()
            ->map(function($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'slug' => $property->slug,
                    'price' => number_format($property->price),
                    'price_type' => $property->price_type,
                    'location' => $property->location->area_name ?? '',
                    'url' => route('properties.show', $property->slug),
                ];
            });

        return response()->json($properties);
    }

    /**
     * Get nearby properties.
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        // Find properties with location coordinates
        $properties = Property::with(['category', 'images'])
            ->where('is_active', true)
            ->whereHas('location', function($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->get()
            ->map(function($property) use ($lat, $lng) {
                $propertyLat = $property->location->latitude;
                $propertyLng = $property->location->longitude;
                
                // Calculate distance using Haversine formula
                $distance = $this->calculateDistance($lat, $lng, $propertyLat, $propertyLng);
                $property->distance = round($distance, 2);
                
                return $property;
            })
            ->sortBy('distance')
            ->take(5)
            ->values();

        return response()->json($properties);
    }

    /**
     * Calculate distance between two coordinates.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
        /**
     * Newsletter subscription.
     */
    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'token' => NewsletterSubscriber::generateToken(),
                'is_active' => true,
                'subscribed_at' => now(),
            ]);

            // You can send a welcome email here
            // Mail::to($subscriber->email)->send(new WelcomeSubscriberMail($subscriber));

            return redirect()->back()->with('success', 'Successfully subscribed to our newsletter!');

        } catch (\Exception $e) {
            \Log::error('Newsletter subscription failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Unable to subscribe. Please try again later.')
                ->withInput();
        }
    }

    /**
     * Newsletter unsubscribe.
     */
    public function newsletterUnsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Invalid unsubscribe link.');
        }

        if (!$subscriber->is_active) {
            return redirect()->route('home')->with('info', 'You are already unsubscribed.');
        }

        $subscriber->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'You have been unsubscribed from our newsletter.');
    }


}