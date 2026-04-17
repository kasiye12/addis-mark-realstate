<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $posts = $query->paginate(15);
        
        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::where('is_published', true)->count(),
            'drafts' => BlogPost::where('is_published', false)->count(),
            'featured' => BlogPost::where('is_featured', true)->count(),
            'total_views' => BlogPost::sum('views'),
        ];

        return view('admin.blog.index', compact('posts', 'stats'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = BlogCategory::active()->get();
        $authors = User::whereIn('role', ['admin', 'agent'])->get();
        return view('admin.blog.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'post_type' => 'required|in:blog,tip,market_update,investment',
            'tags' => 'nullable|string',
            'reading_time' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['published_at'] = $request->boolean('is_published') ? ($request->published_at ?? now()) : null;

        BlogPost::create($validated);

        return redirect()->route('admin.blog.posts.index')->with('success', 'Blog post created!');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::active()->get();
        $authors = User::whereIn('role', ['admin', 'agent'])->get();
        return view('admin.blog.edit', compact('post', 'categories', 'authors'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'post_type' => 'required|in:blog,tip,market_update,investment',
            'tags' => 'nullable|string',
            'reading_time' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($post->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        
        if ($request->boolean('is_published') && !$post->is_published) {
            $validated['published_at'] = $request->published_at ?? now();
        }

        $post->update($validated);

        return redirect()->route('admin.blog.posts.index')->with('success', 'Blog post updated!');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(BlogPost $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();
        return response()->json(['success' => true, 'message' => 'Post deleted!']);
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(BlogPost $post)
    {
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : $post->published_at
        ]);
        return response()->json(['success' => true, 'is_published' => $post->is_published]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(BlogPost $post)
    {
        $post->update(['is_featured' => !$post->is_featured]);
        return response()->json(['success' => true, 'is_featured' => $post->is_featured]);
    }
}