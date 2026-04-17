<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = BlogCategory::withCount('posts')
            ->orderBy('name')
            ->paginate(12);
            
        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $category = BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $category->id,
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'color' => $request->color,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($category->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!'
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(BlogCategory $category)
    {
        if ($category->posts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated posts!'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(BlogCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'Category activated!' : 'Category deactivated!'
        ]);
    }
}