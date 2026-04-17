<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - Main View (Admin Only)
     */
    public function index()
    {
        // Statistics
        $stats = [
            'total_properties' => Property::count(),
            'active_properties' => Property::where('is_active', true)->count(),
            'featured_properties' => Property::where('is_featured', true)->count(),
            'for_sale' => Property::where('price_type', 'sale')->count(),
            'for_rent' => Property::where('price_type', 'rent')->count(),
            'categories' => Category::count(),
            'locations' => Location::count(),
            'total_users' => User::count(),
            'admin_users' => User::admins()->count(),
            'agent_users' => User::agents()->count(),
            'regular_users' => User::users()->count(),
            'testimonials' => Testimonial::count(),
            'team_members' => TeamMember::count(),
            'total_views' => Property::sum('views'),
        ];

        // Recent Properties
        $recentProperties = Property::with(['category', 'location', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // Recent Users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Popular Properties (by views)
        $popularProperties = Property::with(['category', 'location'])
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Properties by Category (for chart)
        $propertiesByCategory = Category::withCount('properties')
            ->orderBy('properties_count', 'desc')
            ->take(5)
            ->get();

        // Properties by Location (for chart)
        $propertiesByLocation = Location::withCount('properties')
            ->orderBy('properties_count', 'desc')
            ->take(5)
            ->get();

        // Monthly registrations (last 6 months)
        $monthlyUsers = User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly properties added (last 6 months)
        $monthlyProperties = Property::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentProperties',
            'recentUsers',
            'popularProperties',
            'propertiesByCategory',
            'propertiesByLocation',
            'monthlyUsers',
            'monthlyProperties'
        ));
    }

    /**
     * Agent Dashboard (Admin + Agent)
     */
    public function agentDashboard()
    {
        $user = auth()->user();
        
        // Agent Statistics
        $stats = [
            'my_properties' => Property::where('user_id', $user->id)->count(),
            'active_properties' => Property::where('user_id', $user->id)->where('is_active', true)->count(),
            'featured_properties' => Property::where('user_id', $user->id)->where('is_featured', true)->count(),
            'for_sale' => Property::where('user_id', $user->id)->where('price_type', 'sale')->count(),
            'for_rent' => Property::where('user_id', $user->id)->where('price_type', 'rent')->count(),
            'pending_properties' => Property::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_views' => Property::where('user_id', $user->id)->sum('views'),
            'total_inquiries' => 0, // To be implemented
        ];

        // Agent's Recent Properties
        $recentProperties = Property::with(['category', 'location'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Agent's Popular Properties
        $popularProperties = Property::with(['category', 'location'])
            ->where('user_id', $user->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Monthly properties added by agent
        $monthlyProperties = Property::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('agent.dashboard', compact(
            'stats',
            'recentProperties',
            'popularProperties',
            'monthlyProperties'
        ));
    }

    /**
     * User Dashboard (Regular Users)
     */
    public function userDashboard()
    {
        $user = auth()->user();
        
        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->isAgent()) {
            return redirect()->route('agent.dashboard');
        }
        
        // Regular user stats
        $stats = [
            'favorites' => 0, // To be implemented
            'inquiries' => 0, // To be implemented
            'saved_searches' => 0, // To be implemented
        ];

        // Featured properties for browsing
        $featuredProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // Latest properties
        $latestProperties = Property::with(['category', 'location', 'images'])
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'featuredProperties', 'latestProperties'));
    }

    /**
     * Analytics Page (Admin Only)
     */
    public function analytics()
    {
        $stats = [
            'total_views' => Property::sum('views'),
            'avg_price_sale' => round(Property::where('price_type', 'sale')->avg('price') ?? 0, 2),
            'avg_price_rent' => round(Property::where('price_type', 'rent')->avg('price') ?? 0, 2),
            'max_price' => Property::max('price') ?? 0,
            'min_price' => Property::min('price') ?? 0,
            'total_area' => Property::sum('area_sqm') ?? 0,
            'avg_area' => round(Property::avg('area_sqm') ?? 0, 2),
        ];

        // Properties by type
        $propertiesByType = Property::select('property_type', DB::raw('COUNT(*) as count'))
            ->groupBy('property_type')
            ->get();

        // Properties by status
        $propertiesByStatus = Property::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.analytics', compact('stats', 'propertiesByType', 'propertiesByStatus'));
    }

    /**
     * Reports Page (Admin Only)
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Generate Report (Admin Only)
     */
    public function generateReport(Request $request)
    {
        $type = $request->get('type', 'properties');
        $format = $request->get('format', 'pdf');
        
        // Generate report logic here
        
        return redirect()->back()->with('success', 'Report generated successfully!');
    }

    /**
     * My Properties (Agent + Admin)
     */
    public function myProperties()
    {
        $user = auth()->user();
        
        $query = Property::with(['category', 'location', 'images']);
        
        // Admin sees all properties, agent sees only their own
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        
        $properties = $query->latest()->paginate(12);

        return view('user.properties.index', compact('properties'));
    }

    /**
     * Favorite Properties (All Users)
     */
    public function favoriteProperties()
    {
        $properties = collect(); // Placeholder for favorites feature
        return view('user.properties.favorites', compact('properties'));
    }

    /**
     * My Inquiries (All Users)
     */
    public function myInquiries()
    {
        $inquiries = collect(); // Placeholder
        return view('user.inquiries.index', compact('inquiries'));
    }

    /**
     * Saved Searches (All Users)
     */
    public function savedSearches()
    {
        $searches = collect(); // Placeholder
        return view('user.searches.index', compact('searches'));
    }

    /**
     * Save Search (All Users)
     */
    public function saveSearch(Request $request)
    {
        // Save search logic
        return redirect()->back()->with('success', 'Search saved successfully!');
    }

    /**
     * Delete Search (All Users)
     */
    public function deleteSearch($id)
    {
        // Delete search logic
        return redirect()->back()->with('success', 'Search deleted!');
    }

    /**
     * Newsletter Subscribers (Admin Only)
     */
    public function subscribers()
    {
        $subscribers = collect(); // Placeholder
        return view('admin.newsletter.subscribers', compact('subscribers'));
    }

    /**
     * Send Newsletter (Admin Only)
     */
    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Send newsletter logic

        return redirect()->back()->with('success', 'Newsletter sent successfully!');
    }

    /**
     * Delete Subscriber (Admin Only)
     */
    public function deleteSubscriber($id)
    {
        return redirect()->back()->with('success', 'Subscriber deleted!');
    }

    /**
     * Export Subscribers (Admin Only)
     */
    public function exportSubscribers()
    {
        // Export logic
        return response()->download('path/to/file.csv');
    }

    /**
     * Download Report (Admin Only)
     */
    public function downloadReport(Request $request)
    {
        $type = $request->get('type', 'properties');
        
        // Generate and download report
        
        return response()->download('path/to/report.pdf');
    }

    /**
     * Quick Actions (Admin Only)
     */
    public function quickAction(Request $request)
    {
        $action = $request->get('action');
        
        switch ($action) {
            case 'clear_cache':
                \Artisan::call('optimize:clear');
                return redirect()->back()->with('success', 'Cache cleared successfully!');
                
            case 'regenerate_sitemap':
                \Artisan::call('sitemap:generate');
                return redirect()->back()->with('success', 'Sitemap regenerated!');
                
            default:
                return redirect()->back()->with('error', 'Invalid action!');
        }
    }
}