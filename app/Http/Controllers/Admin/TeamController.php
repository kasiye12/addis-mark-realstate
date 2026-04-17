<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of team members.
     */
    public function index(Request $request)
    {
        $query = TeamMember::orderBy('sort_order')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $teamMembers = $query->paginate(12);
        
        $stats = [
            'total' => TeamMember::count(),
            'active' => TeamMember::where('is_active', true)->count(),
            'inactive' => TeamMember::where('is_active', false)->count(),
        ];

        return view('admin.team.index', compact('teamMembers', 'stats'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        return view('admin.team.create');
    }

    /**
     * Store a newly created team member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        // Build social links array
        $socialLinks = [];
        foreach (['facebook', 'twitter', 'linkedin', 'instagram'] as $platform) {
            if ($request->filled($platform)) {
                $socialLinks[$platform] = $request->$platform;
            }
        }
        $validated['social_links'] = $socialLinks;

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $request->sort_order ?? TeamMember::max('sort_order') + 1;

        TeamMember::create($validated);

        return redirect()
            ->route('admin.team.index')
            ->with('success', 'Team member created successfully!');
    }

    /**
     * Display the specified team member.
     */
    public function show(TeamMember $teamMember)
    {
        return response()->json($teamMember);
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(TeamMember $teamMember)
    {
        return view('admin.team.edit', compact('teamMember'));
    }

    /**
     * Update the specified team member.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($teamMember->image && Storage::disk('public')->exists($teamMember->image)) {
                Storage::disk('public')->delete($teamMember->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        // Build social links array
        $socialLinks = [];
        foreach (['facebook', 'twitter', 'linkedin', 'instagram'] as $platform) {
            if ($request->filled($platform)) {
                $socialLinks[$platform] = $request->$platform;
            }
        }
        $validated['social_links'] = $socialLinks;

        $validated['is_active'] = $request->boolean('is_active', true);

        $teamMember->update($validated);

        return redirect()
            ->route('admin.team.index')
            ->with('success', 'Team member updated successfully!');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->image && Storage::disk('public')->exists($teamMember->image)) {
            Storage::disk('public')->delete($teamMember->image);
        }

        $teamMember->delete();

        return redirect()
            ->route('admin.team.index')
            ->with('success', 'Team member deleted successfully!');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(TeamMember $teamMember)
    {
        $teamMember->update(['is_active' => !$teamMember->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $teamMember->is_active,
            'message' => $teamMember->is_active ? 'Member activated!' : 'Member deactivated!'
        ]);
    }
}