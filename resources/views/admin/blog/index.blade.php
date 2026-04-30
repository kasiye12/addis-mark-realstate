@extends('layouts.admin')

@section('title', 'Blog Management')

@push('styles')
<style>
    .post-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Blog Management</h1>
            <p class="text-gray-600 mt-1">Manage blog posts, categories, and content</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.blog.categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="ri-folder-line mr-1"></i> Categories
            </a>
            <a href="{{ route('admin.blog.posts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="ri-add-line mr-1"></i> New Post
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Posts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-article-line text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Published</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['published'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Drafts</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['drafts'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ri-draft-line text-gray-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Featured</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['featured'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-line text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Views</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_views'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-eye-line text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 border shadow-sm mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <input type="text" name="search" placeholder="Search posts..." value="{{ request('search') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Types</option>
                <option value="blog" {{ request('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                <option value="tip" {{ request('type') == 'tip' ? 'selected' : '' }}>Real Estate Tip</option>
                <option value="market_update" {{ request('type') == 'market_update' ? 'selected' : '' }}>Market Update</option>
                <option value="investment" {{ request('type') == 'investment' ? 'selected' : '' }}>Investment Advice</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="ri-filter-line mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                <i class="ri-refresh-line mr-1"></i> Reset
            </a>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl p-4 border shadow-sm mb-6 hidden" id="bulkActions">
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">With selected:</span>
            <button onclick="bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                <i class="ri-delete-bin-line mr-1"></i> Delete Selected
            </button>
            <span class="text-sm text-gray-500 ml-2"><span id="selectedCount">0</span> posts selected</span>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-4 text-left">
                        <input type="checkbox" id="selectAll" class="post-checkbox rounded border-gray-300" onchange="toggleSelectAll()">
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50" id="post-row-{{ $post->id }}">
                    <td class="px-4 py-4">
                        <input type="checkbox" class="post-checkbox rounded border-gray-300" value="{{ $post->id }}" onchange="updateBulkActions()">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image_url }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="ri-article-line text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($post->is_featured)
                                        <span class="text-yellow-600"><i class="ri-star-fill"></i> Featured</span>
                                    @endif
                                    {{ $post->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $post->post_type_badge[0] }}">
                            {{ $post->post_type_badge[1] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $post->category->name ?? 'Uncategorized' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="ri-user-line text-xs text-gray-500"></i>
                            </div>
                            {{ $post->author->name ?? 'Unknown' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="togglePublish({{ $post->id }})" class="cursor-pointer">
                            @if($post->is_published)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full hover:bg-green-200">
                                    <i class="ri-checkbox-circle-line mr-1"></i>Published
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200">
                                    <i class="ri-draft-line mr-1"></i>Draft
                                </span>
                            @endif
                        </button>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <i class="ri-eye-line text-gray-400"></i>
                            {{ number_format($post->views) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.blog.posts.preview', $post) }}" target="_blank" 
                               class="p-2 text-gray-500 hover:text-blue-600 transition" title="Preview">
                                <i class="ri-eye-line"></i>
                            </a>
                            <a href="{{ route('admin.blog.posts.edit', $post) }}" 
                               class="p-2 text-gray-500 hover:text-green-600 transition" title="Edit">
                                <i class="ri-edit-line"></i>
                            </a>
                            <button onclick="toggleFeatured({{ $post->id }})" 
                                    class="p-2 text-gray-500 hover:text-yellow-600 transition" 
                                    title="{{ $post->is_featured ? 'Remove Featured' : 'Mark Featured' }}">
                                <i class="ri-star-{{ $post->is_featured ? 'fill' : 'line' }}"></i>
                            </button>
                            <button onclick="deletePost({{ $post->id }})" 
                                    class="p-2 text-gray-500 hover:text-red-600 transition" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i class="ri-article-line text-5xl mb-2 opacity-30 block"></i>
                        <p class="text-lg font-medium">No blog posts found</p>
                        <p class="text-sm mt-1">Get started by creating your first blog post</p>
                        <a href="{{ route('admin.blog.posts.create') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="ri-add-line mr-1"></i> Create New Post
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $posts->appends(request()->query())->links() }}
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    // Select All functionality
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.post-checkbox:not(#selectAll)');
        checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
        updateBulkActions();
    }

    // Update bulk actions visibility
    function updateBulkActions() {
        const selectedCheckboxes = document.querySelectorAll('.post-checkbox:not(#selectAll):checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        if (selectedCheckboxes.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = selectedCheckboxes.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    // Bulk delete
    async function bulkDelete() {
        const selectedCheckboxes = document.querySelectorAll('.post-checkbox:not(#selectAll):checked');
        const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
        
        if (ids.length === 0) return;
        
        if (!confirm(`Are you sure you want to delete ${ids.length} selected posts?`)) return;
        
        try {
            const response = await fetch('{{ route("admin.blog.posts.bulk-delete") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Failed to delete posts');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting posts');
        }
    }

    // Delete single post
    async function deletePost(id) {
        if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) return;
        
        try {
            const response = await fetch(`/admin/blog/posts/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const row = document.getElementById(`post-row-${id}`);
                if (row) {
                    row.remove();
                } else {
                    location.reload();
                }
                alert(data.message || 'Post deleted successfully');
            } else {
                alert(data.message || 'Failed to delete post');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the post');
        }
    }

    // Toggle publish status
    async function togglePublish(id) {
        try {
            const response = await fetch(`/admin/blog/posts/${id}/toggle-publish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to toggle publish status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }

    // Toggle featured status
    async function toggleFeatured(id) {
        try {
            const response = await fetch(`/admin/blog/posts/${id}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to toggle featured status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }

    // Initialize checkboxes state
    document.querySelectorAll('.post-checkbox:not(#selectAll)').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
</script>
@endsection