@extends('layouts.admin')

@section('title', 'Testimonials Management')

@push('styles')
<style>
    .testimonial-card {
        background: white;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .testimonial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15);
    }
    .client-avatar {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        object-fit: cover;
    }
    .rating-star {
        color: #fbbf24;
        font-size: 16px;
    }
    .rating-star.empty {
        color: #e5e7eb;
    }
    .quote-icon {
        position: absolute;
        bottom: 10px;
        right: 10px;
        opacity: 0.1;
        font-size: 40px;
    }
    .modal-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Testimonials Management</h1>
            <p class="text-gray-600 mt-1">Manage client testimonials and reviews</p>
        </div>
        <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i class="ri-add-line"></i> Add Testimonial
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-chat-quote-line text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Active</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Inactive</p>
                    <p class="text-xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ri-close-line text-gray-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Avg Rating</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['avg_rating'] }} <span class="text-sm">/5</span></p>
                </div>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-fill text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">5 Star</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['five_star'] }}</p>
                </div>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-star-fill text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search testimonials..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="rating" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-filter-line"></i> Filter
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Testimonials Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($testimonials as $testimonial)
        <div class="testimonial-card border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 relative">
                <!-- Rating -->
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ri-star-fill rating-star {{ $i <= $testimonial->rating ? '' : 'empty' }}"></i>
                    @endfor
                </div>
                
                <!-- Content -->
                <p class="text-gray-600 mb-4 line-clamp-4 min-h-[80px]">"{{ $testimonial->content }}"</p>
                
                <i class="ri-double-quotes-r quote-icon"></i>
                
                <!-- Client Info -->
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                    <img src="{{ $testimonial->image_url }}" 
                         alt="{{ $testimonial->client_name }}" 
                         class="client-avatar">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $testimonial->client_name }}</h4>
                        @if($testimonial->client_position)
                            <p class="text-sm text-gray-500">{{ $testimonial->client_position }}</p>
                        @endif
                        @if($testimonial->client_company)
                            <p class="text-xs text-gray-400">{{ $testimonial->client_company }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Status Badge & Actions -->
                <div class="flex items-center justify-between mt-3">
                    <span class="px-2 py-1 text-xs rounded-full {{ $testimonial->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    
                    <div class="flex items-center gap-1">
                        <button onclick="toggleStatus({{ $testimonial->id }})" 
                                class="p-2 text-gray-400 hover:text-green-600 transition"
                                title="{{ $testimonial->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="ri-toggle-{{ $testimonial->is_active ? 'fill' : 'line' }}"></i>
                        </button>
                        <button onclick="editTestimonial({{ $testimonial->id }})" 
                                class="p-2 text-gray-400 hover:text-blue-600 transition"
                                title="Edit">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button onclick="deleteTestimonial({{ $testimonial->id }})" 
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <i class="ri-chat-quote-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No testimonials found</h3>
                <p class="text-gray-500 mb-4">Add your first client testimonial to showcase your success.</p>
                <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-1"></i> Add Testimonial
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($testimonials->hasPages())
    <div class="mt-6">
        {{ $testimonials->links() }}
    </div>
    @endif
</div>

<!-- Testimonial Modal -->
<div id="testimonialModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Add Testimonial</h2>
        </div>
        
        <form id="testimonialForm" enctype="multipart/form-data">
            <input type="hidden" id="testimonialId">
            
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Name *</label>
                    <input type="text" id="clientName" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" id="clientPosition" placeholder="e.g., CEO"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" id="clientCompany" placeholder="Company name"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Photo</label>
                    <div class="flex items-center gap-4">
                        <img id="imagePreview" src="" class="w-16 h-16 rounded-lg object-cover bg-gray-100 hidden">
                        <input type="file" name="client_image" id="clientImage" accept="image/*"
                               class="flex-1 px-4 py-2 border border-gray-200 rounded-lg"
                               onchange="previewImage(this)">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Testimonial Content *</label>
                    <textarea id="content" rows="4" required
                              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="What did the client say?"></textarea>
                    <p class="text-xs text-gray-500 mt-1"><span id="charCount">0</span>/1000 characters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                    <div class="flex gap-2" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="rating-btn text-3xl text-gray-300 hover:text-yellow-400 transition">
                                <i class="ri-star-fill"></i>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" id="rating" value="5" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" id="sortOrder" value="0" min="0"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="isActive" checked class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Save Testimonial
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('testimonialModal');
    const form = document.getElementById('testimonialForm');
    let currentRating = 5;

    // Character counter
    document.getElementById('content').addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });

    // Rating system
    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating').value = rating;
        
        const stars = document.querySelectorAll('.rating-btn');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.add('text-gray-300');
                star.classList.remove('text-yellow-400');
            }
        });
    }

    // Image preview
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openModal() {
        document.getElementById('modalTitle').textContent = 'Add Testimonial';
        document.getElementById('testimonialId').value = '';
        form.reset();
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('charCount').textContent = '0';
        setRating(5);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function editTestimonial(id) {
        fetch(`/admin/testimonials/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Testimonial';
                document.getElementById('testimonialId').value = data.id;
                document.getElementById('clientName').value = data.client_name;
                document.getElementById('clientPosition').value = data.client_position || '';
                document.getElementById('clientCompany').value = data.client_company || '';
                document.getElementById('content').value = data.content;
                document.getElementById('sortOrder').value = data.sort_order;
                document.getElementById('isActive').checked = data.is_active;
                document.getElementById('charCount').textContent = data.content.length;
                
                if (data.client_image) {
                    document.getElementById('imagePreview').src = data.image_url;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                
                setRating(data.rating);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('testimonialId').value;
        const url = id ? `/admin/testimonials/${id}` : '/admin/testimonials';
        const method = id ? 'POST' : 'POST';
        
        const formData = new FormData();
        formData.append('_method', id ? 'PUT' : 'POST');
        formData.append('client_name', document.getElementById('clientName').value);
        formData.append('client_position', document.getElementById('clientPosition').value);
        formData.append('client_company', document.getElementById('clientCompany').value);
        formData.append('content', document.getElementById('content').value);
        formData.append('rating', document.getElementById('rating').value);
        formData.append('sort_order', document.getElementById('sortOrder').value);
        formData.append('is_active', document.getElementById('isActive').checked ? '1' : '0');
        
        const imageFile = document.getElementById('clientImage').files[0];
        if (imageFile) {
            formData.append('client_image', imageFile);
        }
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });

    function toggleStatus(id) {
        fetch(`/admin/testimonials/${id}/toggle-status`, {
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

    function deleteTestimonial(id) {
        if (!confirm('Are you sure you want to delete this testimonial?')) return;
        
        fetch(`/admin/testimonials/${id}`, {
            method: 'DELETE',
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

    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endsection