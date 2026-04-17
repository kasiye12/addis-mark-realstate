@extends('layouts.admin')

@section('title', 'Blog Categories')

@push('styles')
<style>
    .color-preview {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }
    .category-card {
        transition: all 0.2s ease;
    }
    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Blog Categories</h1>
            <p class="text-gray-600 mt-1">Manage categories for your blog posts</p>
        </div>
        <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i class="ri-add-line"></i> Add Category
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Total Categories</p>
            <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Active Categories</p>
            <p class="text-2xl font-bold text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border">
            <p class="text-sm text-gray-500">Total Posts</p>
            <p class="text-2xl font-bold text-blue-600">{{ $categories->sum('posts_count') }}</p>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($categories as $category)
        <div class="category-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="color-preview" style="background-color: {{ $category->color }}"></div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="flex items-center gap-4 text-sm mb-4">
                    <span class="flex items-center gap-1 text-gray-600">
                        <i class="ri-article-line"></i>
                        <span>{{ $category->posts_count }} Posts</span>
                    </span>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-500 text-xs">{{ $category->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <a href="{{ route('blog.category', $category->slug) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700">
                        <i class="ri-external-link-line mr-1"></i> View
                    </a>
                    <div class="flex items-center gap-1">
                        <button onclick="toggleStatus({{ $category->id }})" 
                                class="p-2 text-gray-400 hover:text-green-600 transition"
                                title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="ri-toggle-{{ $category->is_active ? 'fill' : 'line' }}"></i>
                        </button>
                        <button onclick="editCategory({{ $category }})" 
                                class="p-2 text-gray-400 hover:text-blue-600 transition"
                                title="Edit">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button onclick="deleteCategory({{ $category->id }})" 
                                class="p-2 text-gray-400 hover:text-red-600 transition"
                                title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <i class="ri-folder-line text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-500 mb-4">Create your first blog category to organize your posts.</p>
                <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-1"></i> Add Category
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-xl">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Add Category</h2>
        </div>
        
        <form id="categoryForm">
            <input type="hidden" id="categoryId">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                    <input type="text" id="categoryName" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., Real Estate Tips">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color *</label>
                    <div class="flex gap-2">
                        <input type="color" id="categoryColor" value="#2563eb" required
                               class="w-14 h-10 rounded-lg border border-gray-200 cursor-pointer">
                        <input type="text" id="categoryColorText" value="#2563eb"
                               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 uppercase"
                               placeholder="#HEXCODE" maxlength="7">
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="setColor('#2563eb')" class="w-8 h-8 rounded bg-blue-600" title="Blue"></button>
                        <button type="button" onclick="setColor('#10b981')" class="w-8 h-8 rounded bg-green-600" title="Green"></button>
                        <button type="button" onclick="setColor('#f59e0b')" class="w-8 h-8 rounded bg-amber-500" title="Amber"></button>
                        <button type="button" onclick="setColor('#ef4444')" class="w-8 h-8 rounded bg-red-500" title="Red"></button>
                        <button type="button" onclick="setColor('#8b5cf6')" class="w-8 h-8 rounded bg-purple-500" title="Purple"></button>
                        <button type="button" onclick="setColor('#ec4899')" class="w-8 h-8 rounded bg-pink-500" title="Pink"></button>
                    </div>
                </div>
                
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="categoryActive" checked class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Save Category
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    
    // Color sync
    document.getElementById('categoryColor').addEventListener('input', function() {
        document.getElementById('categoryColorText').value = this.value;
    });
    
    document.getElementById('categoryColorText').addEventListener('input', function() {
        let val = this.value;
        if (!val.startsWith('#')) val = '#' + val;
        if (val.match(/^#[0-9A-Fa-f]{6}$/)) {
            document.getElementById('categoryColor').value = val;
        }
        this.value = this.value.toUpperCase();
    });
    
    function setColor(color) {
        document.getElementById('categoryColor').value = color;
        document.getElementById('categoryColorText').value = color.toUpperCase();
    }
    
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Add Category';
        document.getElementById('categoryId').value = '';
        form.reset();
        document.getElementById('categoryColor').value = '#2563eb';
        document.getElementById('categoryColorText').value = '#2563EB';
        document.getElementById('categoryActive').checked = true;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    function editCategory(category) {
        document.getElementById('modalTitle').textContent = 'Edit Category';
        document.getElementById('categoryId').value = category.id;
        document.getElementById('categoryName').value = category.name;
        document.getElementById('categoryColor').value = category.color;
        document.getElementById('categoryColorText').value = category.color.toUpperCase();
        document.getElementById('categoryActive').checked = category.is_active;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('categoryId').value;
        const url = id ? `/admin/blog/categories/${id}` : '/admin/blog/categories';
        const method = id ? 'PUT' : 'POST';
        
        const data = {
            name: document.getElementById('categoryName').value,
            color: document.getElementById('categoryColor').value,
            is_active: document.getElementById('categoryActive').checked,
            _token: '{{ csrf_token() }}'
        };
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        });
    });
    
    function toggleStatus(id) {
        fetch(`/admin/blog/categories/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
        });
    }
    
    function deleteCategory(id) {
        if (!confirm('Are you sure you want to delete this category?')) return;
        
        fetch(`/admin/blog/categories/${id}`, {
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
                alert(data.message);
            }
        });
    }
    
    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endsection