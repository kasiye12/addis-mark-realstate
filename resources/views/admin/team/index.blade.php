@extends('layouts.admin')

@section('title', 'Team Management')

@push('styles')
<style>
    .team-card {
        background: white;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15);
    }
    .member-avatar {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .social-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        background: #f3f4f6;
        color: #4b5563;
    }
    .social-icon:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Team Management</h1>
            <p class="text-gray-600 mt-1">Manage your real estate agents and team members</p>
        </div>
        <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i class="ri-add-line"></i> Add Team Member
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Members</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-team-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Inactive</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ri-close-line text-xl text-gray-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($teamMembers as $member)
        <div class="team-card border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex flex-col items-center text-center">
                    <img src="{{ $member->image_url }}" 
                         alt="{{ $member->name }}" 
                         class="member-avatar mb-4">
                    
                    <h3 class="font-bold text-lg text-gray-900">{{ $member->name }}</h3>
                    <p class="text-blue-600 text-sm font-medium mb-2">{{ $member->position }}</p>
                    
                    @if($member->bio)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $member->bio }}</p>
                    @endif
                    
                    <!-- Contact Info -->
                    <div class="w-full space-y-2 mb-4">
                        @if($member->email)
                        <a href="mailto:{{ $member->email }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600">
                            <i class="ri-mail-line"></i> {{ $member->email }}
                        </a>
                        @endif
                        @if($member->phone)
                        <a href="tel:{{ $member->phone }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600">
                            <i class="ri-phone-line"></i> {{ $member->phone }}
                        </a>
                        @endif
                    </div>
                    
                    <!-- Social Links - FIXED -->
                    @php
                        $socialLinks = is_array($member->social_links) ? $member->social_links : json_decode($member->social_links, true);
                    @endphp
                    @if(!empty($socialLinks))
                    <div class="flex gap-2 justify-center mb-4">
                        @if(!empty($socialLinks['facebook']))
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank" class="social-icon">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        @endif
                        @if(!empty($socialLinks['twitter']))
                        <a href="{{ $socialLinks['twitter'] }}" target="_blank" class="social-icon">
                            <i class="ri-twitter-x-fill"></i>
                        </a>
                        @endif
                        @if(!empty($socialLinks['linkedin']))
                        <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="social-icon">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                        @endif
                        @if(!empty($socialLinks['instagram']))
                        <a href="{{ $socialLinks['instagram'] }}" target="_blank" class="social-icon">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Status & Actions -->
                    <div class="flex items-center justify-between w-full pt-4 border-t border-gray-100">
                        <span class="px-2 py-1 text-xs rounded-full {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        
                        <div class="flex items-center gap-1">
                            <button onclick="toggleStatus({{ $member->id }})" 
                                    class="p-2 text-gray-400 hover:text-green-600 transition"
                                    title="{{ $member->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="ri-toggle-{{ $member->is_active ? 'fill' : 'line' }}"></i>
                            </button>
                            <button onclick="editMember({{ $member->id }})" 
                                    class="p-2 text-gray-400 hover:text-blue-600 transition"
                                    title="Edit">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button onclick="deleteMember({{ $member->id }})" 
                                    class="p-2 text-gray-400 hover:text-red-600 transition"
                                    title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <i class="ri-team-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No team members found</h3>
                <p class="text-gray-500 mb-4">Add your first team member to showcase your experts.</p>
                <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-add-line mr-1"></i> Add Team Member
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($teamMembers->hasPages())
    <div class="mt-6">
        {{ $teamMembers->links() }}
    </div>
    @endif
</div>

<!-- Team Member Modal -->
<div id="teamModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Add Team Member</h2>
        </div>
        
        <form id="teamForm" enctype="multipart/form-data">
            <input type="hidden" id="memberId">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" id="name" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                    <input type="text" id="position" required placeholder="e.g., Senior Real Estate Agent"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea id="bio" rows="3" placeholder="Short biography..."
                              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <img id="imagePreview" src="" class="w-16 h-16 rounded-lg object-cover bg-gray-100 hidden">
                        <input type="file" name="image" id="image" accept="image/*"
                               class="flex-1 px-4 py-2 border border-gray-200 rounded-lg"
                               onchange="previewImage(this)">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" placeholder="member@example.com"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="phone" placeholder="+251..."
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Social Links</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500">Facebook</label>
                            <input type="url" id="facebook" placeholder="https://facebook.com/..."
                                   class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Twitter/X</label>
                            <input type="url" id="twitter" placeholder="https://twitter.com/..."
                                   class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">LinkedIn</label>
                            <input type="url" id="linkedin" placeholder="https://linkedin.com/in/..."
                                   class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Instagram</label>
                            <input type="url" id="instagram" placeholder="https://instagram.com/..."
                                   class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" id="sortOrder" value="0" min="0"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                </div>
                
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="isActive" checked class="rounded">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3 sticky bottom-0">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-save-line mr-1"></i> Save Member
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('teamModal');
    const form = document.getElementById('teamForm');

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
        document.getElementById('modalTitle').textContent = 'Add Team Member';
        document.getElementById('memberId').value = '';
        form.reset();
        document.getElementById('imagePreview').classList.add('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function editMember(id) {
        fetch(`/admin/team/${id}`)
            .then(response => response.json())
            .then(data => {
                const member = data.member;
                document.getElementById('modalTitle').textContent = 'Edit Team Member';
                document.getElementById('memberId').value = member.id;
                document.getElementById('name').value = member.name;
                document.getElementById('position').value = member.position;
                document.getElementById('bio').value = member.bio || '';
                document.getElementById('email').value = member.email || '';
                document.getElementById('phone').value = member.phone || '';
                document.getElementById('sortOrder').value = member.sort_order;
                document.getElementById('isActive').checked = member.is_active;
                
                // Parse social links
                let socialLinks = member.social_links;
                if (typeof socialLinks === 'string') {
                    socialLinks = JSON.parse(socialLinks);
                }
                if (socialLinks) {
                    document.getElementById('facebook').value = socialLinks.facebook || '';
                    document.getElementById('twitter').value = socialLinks.twitter || '';
                    document.getElementById('linkedin').value = socialLinks.linkedin || '';
                    document.getElementById('instagram').value = socialLinks.instagram || '';
                }
                
                if (member.image) {
                    document.getElementById('imagePreview').src = member.image_url || `{{ asset('storage') }}/${member.image}`;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('memberId').value;
        const url = id ? `/admin/team/${id}` : '/admin/team';
        const method = id ? 'PUT' : 'POST';
        
        const formData = new FormData();
        if (id) formData.append('_method', 'PUT');
        formData.append('name', document.getElementById('name').value);
        formData.append('position', document.getElementById('position').value);
        formData.append('bio', document.getElementById('bio').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('sort_order', document.getElementById('sortOrder').value);
        formData.append('is_active', document.getElementById('isActive').checked ? '1' : '0');
        
        const socialLinks = {
            facebook: document.getElementById('facebook').value,
            twitter: document.getElementById('twitter').value,
            linkedin: document.getElementById('linkedin').value,
            instagram: document.getElementById('instagram').value,
        };
        formData.append('social_links', JSON.stringify(socialLinks));
        
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
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
        fetch(`/admin/team/${id}/toggle-status`, {
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

    function deleteMember(id) {
        if (!confirm('Are you sure you want to delete this team member?')) return;
        
        fetch(`/admin/team/${id}`, {
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endsection