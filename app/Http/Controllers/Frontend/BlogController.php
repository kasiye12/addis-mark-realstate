<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author'])
            ->published()
            ->latest('published_at');
        
        // Filter by post type
        if ($request->filled('type')) {
            $query->where('post_type', $request->type);
        }
        
        $posts = $query->paginate(10);
        
        $categories = BlogCategory::active()
            ->withCount(['posts' => function($q) {
                $q->published();
            }])
            ->get();
        
        $featuredPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();
        
        // Get popular tags
        $popularTags = BlogPost::published()
            ->whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(10)
            ->keys()
            ->toArray();
        
        return view('frontend.blog.index', compact('posts', 'categories', 'featuredPosts', 'popularTags'));
    }

    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::with(['author'])
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(10);
        
        $categories = BlogCategory::active()
            ->withCount(['posts' => function($q) {
                $q->published();
            }])
            ->get();
        
        $featuredPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('frontend.blog.category', compact('posts', 'category', 'categories', 'featuredPosts'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($tag)
    {
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->whereJsonContains('tags', $tag)
            ->latest('published_at')
            ->paginate(10);
        
        $categories = BlogCategory::active()
            ->withCount(['posts' => function($q) {
                $q->published();
            }])
            ->get();
        
        $featuredPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('frontend.blog.tag', compact('posts', 'tag', 'categories', 'featuredPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        
        $post->increment('views');
        
        $relatedPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->where('id', '!=', $post->id)
            ->where(function($query) use ($post) {
                $query->where('category_id', $post->category_id)
                      ->orWhere('post_type', $post->post_type);
            })
            ->latest('published_at')
            ->take(3)
            ->get();
        
        $categories = BlogCategory::active()->get();
        
        return view('frontend.blog.show', compact('post', 'relatedPosts', 'categories'));
    }
}