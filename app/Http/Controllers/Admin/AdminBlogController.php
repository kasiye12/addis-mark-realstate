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
        $query = BlogPost::with(['category', 'author']);

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('excerpt', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            } elseif ($request->status === 'scheduled') {
                $query->where('is_published', true)
                      ->where('published_at', '>', now());
            } elseif ($request->status === 'featured') {
                $query->featured();
            }
        }

        // Apply post type filter
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Apply author filter
        if ($request->filled('author')) {
            $query->byAuthor($request->author);
        }

        // Apply sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['title', 'created_at', 'published_at', 'views', 'reading_time'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $posts = $query->paginate($request->get('per_page', 15))
                       ->withQueryString();
        
        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::where('is_published', true)
                                   ->where('published_at', '<=', now())
                                   ->count(),
            'drafts' => BlogPost::where('is_published', false)->count(),
            'scheduled' => BlogPost::where('is_published', true)
                                   ->where('published_at', '>', now())
                                   ->count(),
            'featured' => BlogPost::where('is_featured', true)->count(),
            'total_views' => BlogPost::sum('views'),
        ];

        $categories = BlogCategory::active()->get();
        $authors = User::whereIn('role', ['admin', 'agent'])->get();
        $postTypes = [
            'blog' => 'Blog',
            'tip' => 'Real Estate Tip',
            'market_update' => 'Market Update',
            'investment' => 'Investment Advice',
        ];

        return view('admin.blog.index', compact(
            'posts', 'stats', 'categories', 'authors', 'postTypes'
        ));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = BlogCategory::active()->get();
        $authors = User::whereIn('role', ['admin', 'agent'])->get();
        $postTypes = [
            'blog' => 'Blog',
            'tip' => 'Real Estate Tip',
            'market_update' => 'Market Update',
            'investment' => 'Investment Advice',
        ];
        
        return view('admin.blog.create', compact('categories', 'authors', 'postTypes'));
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'post_type' => 'required|in:blog,tip,market_update,investment',
            'tags' => 'nullable|string',
            'reading_time' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/featured', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('blog/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Process tags
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $validated['tags'] = [];
        }

        // Set boolean fields
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        
        // Set published_at based on publish status
        if ($request->boolean('is_published')) {
            $validated['published_at'] = $request->published_at ?? now();
        } else {
            $validated['published_at'] = $request->published_at;
        }

        // Set default reading time if not provided
        if (!$request->filled('reading_time')) {
            $validated['reading_time'] = $this->calculateReadingTime($validated['content']);
        }

        $post = BlogPost::create($validated);

        return redirect()
            ->route('admin.blog.posts.index')
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $post)
    {
        $post->load(['category', 'author']);
        $categories = BlogCategory::active()->get();
        $authors = User::whereIn('role', ['admin', 'agent'])->get();
        $postTypes = [
            'blog' => 'Blog',
            'tip' => 'Real Estate Tip',
            'market_update' => 'Market Update',
            'investment' => 'Investment Advice',
        ];
        
        return view('admin.blog.edit', compact('post', 'categories', 'authors', 'postTypes'));
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'string',
            'video_url' => 'nullable|url|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'post_type' => 'required|in:blog,tip,market_update,investment',
            'tags' => 'nullable|string',
            'reading_time' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Update slug if title changed
        if ($post->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/featured', 'public');
        }

        // Handle gallery images
        $currentGallery = $post->gallery_images ?? [];
        
        // Remove selected images
        if ($request->has('remove_gallery_images')) {
            foreach ($request->remove_gallery_images as $imageToRemove) {
                Storage::disk('public')->delete($imageToRemove);
                $currentGallery = array_values(array_diff($currentGallery, [$imageToRemove]));
            }
        }
        
        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGallery[] = $image->store('blog/gallery', 'public');
            }
        }
        
        $validated['gallery_images'] = $currentGallery;

        // Process tags
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $validated['tags'] = [];
        }

        // Set boolean fields
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        
        // Set published_at if publishing for the first time
        if ($request->boolean('is_published') && !$post->is_published) {
            $validated['published_at'] = $request->published_at ?? now();
        } elseif (!$request->boolean('is_published')) {
            $validated['published_at'] = $request->published_at;
        }

        // Update reading time if content changed and not manually set
        if ($post->content !== $validated['content'] && !$request->filled('reading_time')) {
            $validated['reading_time'] = $this->calculateReadingTime($validated['content']);
        }

        $post->update($validated);

        return redirect()
            ->route('admin.blog.posts.index')
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(BlogPost $post)
    {
        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        // Delete gallery images
        if ($post->gallery_images) {
            foreach ($post->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $post->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Post deleted successfully!'
        ]);
    }

    /**
     * Bulk delete posts.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blog_posts,id',
        ]);

        $posts = BlogPost::whereIn('id', $request->ids)->get();
        
        foreach ($posts as $post) {
            // Delete featured image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            // Delete gallery images
            if ($post->gallery_images) {
                foreach ($post->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $post->delete();
        }

        return response()->json([
            'success' => true, 
            'message' => count($posts) . ' posts deleted successfully!'
        ]);
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(BlogPost $post)
    {
        if ($post->is_published) {
            $post->unpublish();
        } else {
            $post->publish();
        }
        
        return response()->json([
            'success' => true, 
            'is_published' => $post->fresh()->is_published,
            'message' => $post->is_published ? 'Post published!' : 'Post unpublished!'
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(BlogPost $post)
    {
        $post->update(['is_featured' => !$post->is_featured]);
        
        return response()->json([
            'success' => true, 
            'is_featured' => $post->fresh()->is_featured,
            'message' => $post->is_featured ? 'Post marked as featured!' : 'Post unmarked as featured!'
        ]);
    }

    /**
     * Duplicate a post.
     */
    public function duplicate(BlogPost $post)
    {
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Copy)';
        $newPost->slug = Str::slug($newPost->title);
        $newPost->is_published = false;
        $newPost->published_at = null;
        $newPost->views = 0;
        $newPost->created_at = now();
        $newPost->updated_at = now();
        
        // Copy featured image if exists
        if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
            $newImagePath = 'blog/featured/' . uniqid() . '_' . basename($post->featured_image);
            Storage::disk('public')->copy($post->featured_image, $newImagePath);
            $newPost->featured_image = $newImagePath;
            
            // Copy gallery images if exist
            if ($post->gallery_images) {
                $newGalleryImages = [];
                foreach ($post->gallery_images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        $newImagePath = 'blog/gallery/' . uniqid() . '_' . basename($image);
                        Storage::disk('public')->copy($image, $newImagePath);
                        $newGalleryImages[] = $newImagePath;
                    }
                }
                $newPost->gallery_images = $newGalleryImages;
            }
        }
        
        $newPost->save();

        return redirect()
            ->route('admin.blog.posts.edit', $newPost)
            ->with('success', 'Post duplicated successfully!');
    }

    /**
     * Calculate estimated reading time based on content length.
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return max(1, $readingTime);
    }

    /**
     * Preview a post.
     */
    public function preview(BlogPost $post)
    {
        return view('admin.blog.preview', compact('post'));
    }
}