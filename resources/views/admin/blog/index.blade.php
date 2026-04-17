@extends('layouts.admin')

@section('title', 'Blog Management')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Blog Management</h1>
            <p class="text-gray-600 mt-1">Manage blog posts, categories, and content</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.blog.categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="ri-folder-line mr-1"></i> Categories
            </a>
            <a href="{{ route('admin.blog.posts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="ri-add-line mr-1"></i> New Post
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Total Posts</p>
            <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Published</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['published'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Drafts</p>
            <p class="text-2xl font-bold text-gray-600">{{ $stats['drafts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Featured</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['featured'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Total Views</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_views'] ?? 0) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 border mb-6">
        <form method="GET" class="grid grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search posts..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg text-center">Reset</a>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($post->featured_image)
                                <img src="{{ route('file.show', ['path' => $post->featured_image]) }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="ri-article-line text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium">{{ $post->title }}</p>
                                <p class="text-sm text-gray-500">{{ $post->category->name ?? 'Uncategorized' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $post->post_type_badge[0] }}">{{ $post->post_type_badge[1] }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $post->author->name }}</td>
                    <td class="px-6 py-4">
                        @if($post->is_published)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Published</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $post->views }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600" title="View">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="{{ route('admin.blog.posts.edit', $post) }}" class="p-2 text-gray-500 hover:text-green-600" title="Edit">
                            <i class="ri-edit-line"></i>
                        </a>
                        <button onclick="deletePost({{ $post->id }})" class="p-2 text-gray-500 hover:text-red-600" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="ri-article-line text-4xl mb-2 opacity-50"></i>
                        <p>No blog posts found.</p>
                        <a href="{{ route('admin.blog.posts.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Create your first post</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>

<script>
    function deletePost(id) {
        if (!confirm('Are you sure you want to delete this post?')) return;
        
        fetch(`/admin/blog/posts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete post');
            }
        });
    }
</script>
@endsection