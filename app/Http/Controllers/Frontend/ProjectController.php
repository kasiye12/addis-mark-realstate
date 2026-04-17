<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Project::where('is_active', true);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'ongoing'); // Default to ongoing
        }
        
        $projects = $query->latest()->paginate(12);
        
        // Get counts for tabs
        $counts = [
            'ongoing' => Project::where('is_active', true)->where('status', 'ongoing')->count(),
            'completed' => Project::where('is_active', true)->where('status', 'completed')->count(),
            'upcoming' => Project::where('is_active', true)->where('status', 'upcoming')->count(),
        ];
        
        return view('frontend.projects.index', compact('projects', 'counts'));
    }

    /**
     * Display the specified project.
     */
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $project->increment('views');
        
        $relatedProjects = Project::where('is_active', true)
            ->where('id', '!=', $project->id)
            ->where('status', $project->status)
            ->latest()
            ->take(3)
            ->get();
        
        return view('frontend.projects.show', compact('project', 'relatedProjects'));
    }
}