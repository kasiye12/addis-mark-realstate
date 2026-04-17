<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate(15);
        
        $stats = [
            'total' => Project::count(),
            'ongoing' => Project::where('status', 'ongoing')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'upcoming' => Project::where('status', 'upcoming')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:ongoing,completed,upcoming',
            'featured_image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'starting_price' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'developer' => 'nullable|string|max:255',
            'amenities' => 'nullable|string',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        if ($request->filled('amenities')) {
            $validated['amenities'] = array_map('trim', explode(',', $request->amenities));
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created!');
    }

    public function edit(Project $project)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:ongoing,completed,upcoming',
            'featured_image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'starting_price' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'developer' => 'nullable|string|max:255',
            'amenities' => 'nullable|string',
            'specifications' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($project->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            if ($project->featured_image) {
                Storage::disk('public')->delete($project->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = $project->gallery ?? [];
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery'] = $gallery;
        }

        if ($request->filled('amenities')) {
            $validated['amenities'] = array_map('trim', explode(',', $request->amenities));
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated!');
    }

    public function destroy(Project $project)
    {
        if ($project->featured_image) {
            Storage::disk('public')->delete($project->featured_image);
        }
        if ($project->gallery) {
            foreach ($project->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $project->delete();
        return response()->json(['success' => true, 'message' => 'Project deleted!']);
    }

    public function toggleFeatured(Project $project)
    {
        $project->update(['is_featured' => !$project->is_featured]);
        return response()->json(['success' => true]);
    }

    public function toggleStatus(Project $project)
    {
        $project->update(['is_active' => !$project->is_active]);
        return response()->json(['success' => true]);
    }
}