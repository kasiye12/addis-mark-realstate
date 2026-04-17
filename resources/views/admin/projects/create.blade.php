@extends('layouts.admin')

@section('title', 'Add New Project')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .gallery-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Project</h1>
            <p class="text-gray-600 mt-1">Create a new real estate development project</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-2.5 border rounded-lg @error('title') border-red-300 @enderror">
                            @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 border rounded-lg">{{ old('short_description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Brief summary displayed in project cards</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Description *</label>
                            <textarea name="description" rows="6" required class="w-full px-4 py-2.5 border rounded-lg @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Specifications</h3>
                    
                    <div id="specifications-container" class="space-y-3">
                        <div class="specification-row flex gap-3">
                            <input type="text" name="specifications[key][]" placeholder="Key (e.g., Total Units)" class="flex-1 px-4 py-2 border rounded-lg">
                            <input type="text" name="specifications[value][]" placeholder="Value (e.g., 120)" class="flex-1 px-4 py-2 border rounded-lg">
                            <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addSpecification()" class="mt-3 text-sm text-blue-600 hover:text-blue-700">
                        <i class="ri-add-line mr-1"></i> Add Specification
                    </button>
                </div>
                
                <!-- Amenities -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Amenities</h3>
                    
                    <input type="text" name="amenities" value="{{ old('amenities') }}" 
                           placeholder="e.g., Swimming Pool, Gym, 24/7 Security, Garden"
                           class="w-full px-4 py-2.5 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Comma separated list of amenities</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Project Details -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Project Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location *</label>
                            <input type="text" name="location" value="{{ old('location') }}" required
                                   class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full px-4 py-2.5 border rounded-lg">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full px-4 py-2.5 border rounded-lg">
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Starting Price (ETB)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price') }}" step="0.01"
                                   class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Developer</label>
                            <input type="text" name="developer" value="{{ old('developer') }}"
                                   class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}"
                                       class="w-full px-4 py-2.5 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Completion Date</label>
                                <input type="date" name="completion_date" value="{{ old('completion_date') }}"
                                       class="w-full px-4 py-2.5 border rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Media -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Media</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                            <input type="file" name="featured_image" accept="image/*"
                                   class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gallery Images</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple
                                   class="w-full px-4 py-2.5 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">You can select multiple images</p>
                        </div>
                    </div>
                </div>
                
                <!-- Options -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Options</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Featured Project</span>
                        </label>
                        
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Active (visible on website)</span>
                        </label>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="bg-white rounded-xl p-6 border">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="ri-save-line mr-1"></i> Create Project
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function addSpecification() {
        const container = document.getElementById('specifications-container');
        const row = document.createElement('div');
        row.className = 'specification-row flex gap-3';
        row.innerHTML = `
            <input type="text" name="specifications[key][]" placeholder="Key" class="flex-1 px-4 py-2 border rounded-lg">
            <input type="text" name="specifications[value][]" placeholder="Value" class="flex-1 px-4 py-2 border rounded-lg">
            <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                <i class="ri-delete-bin-line"></i>
            </button>
        `;
        container.appendChild(row);
    }
    
    function removeSpecification(button) {
        const rows = document.querySelectorAll('.specification-row');
        if (rows.length > 1) {
            button.closest('.specification-row').remove();
        }
    }
</script>
@endsection