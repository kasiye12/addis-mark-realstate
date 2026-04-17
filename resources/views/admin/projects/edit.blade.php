@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Project</h1>
            <p class="text-gray-600 mt-1">Update project information</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="ri-arrow-left-line mr-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                            <input type="text" name="title" value="{{ old('title', $project->title) }}" required class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 border rounded-lg">{{ old('short_description', $project->short_description) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Description *</label>
                            <textarea name="description" rows="6" required class="w-full px-4 py-2.5 border rounded-lg">{{ old('description', $project->description) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Specifications</h3>
                    
                    <div id="specifications-container" class="space-y-3">
                        @php
                            $specs = $project->specifications;
                            if (is_string($specs)) {
                                $specs = json_decode($specs, true);
                            }
                        @endphp
                        
                        @if(!empty($specs) && is_array($specs))
                            @foreach($specs as $key => $value)
                                @if(is_string($value) || is_numeric($value))
                                <div class="specification-row flex gap-3">
                                    <input type="text" name="specifications[key][]" value="{{ $key }}" class="flex-1 px-4 py-2 border rounded-lg">
                                    <input type="text" name="specifications[value][]" value="{{ $value }}" class="flex-1 px-4 py-2 border rounded-lg">
                                    <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="specification-row flex gap-3">
                                <input type="text" name="specifications[key][]" placeholder="Key (e.g., Total Units)" class="flex-1 px-4 py-2 border rounded-lg">
                                <input type="text" name="specifications[value][]" placeholder="Value (e.g., 120)" class="flex-1 px-4 py-2 border rounded-lg">
                                <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addSpecification()" class="mt-3 text-sm text-blue-600 hover:text-blue-700">
                        <i class="ri-add-line mr-1"></i> Add Specification
                    </button>
                </div>
                
                <!-- Amenities -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Amenities</h3>
                    
                    @php
                        $amenities = $project->amenities;
                        if (is_string($amenities)) {
                            $amenities = json_decode($amenities, true);
                        }
                        $amenitiesString = is_array($amenities) ? implode(', ', $amenities) : $amenities;
                    @endphp
                    
                    <input type="text" name="amenities" value="{{ old('amenities', $amenitiesString) }}" 
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
                            <input type="text" name="location" value="{{ old('location', $project->location) }}" required class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full px-4 py-2.5 border rounded-lg">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full px-4 py-2.5 border rounded-lg">
                                <option value="ongoing" {{ old('status', $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="upcoming" {{ old('status', $project->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Starting Price (ETB)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price', $project->starting_price) }}" step="0.01" class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Developer</label>
                            <input type="text" name="developer" value="{{ old('developer', $project->developer) }}" class="w-full px-4 py-2.5 border rounded-lg">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Completion Date</label>
                                <input type="date" name="completion_date" value="{{ old('completion_date', $project->completion_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border rounded-lg">
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
                            @if($project->featured_image)
                                <img src="{{ route('file.show', ['path' => $project->featured_image]) }}" class="w-full h-32 object-cover rounded-lg mb-2">
                            @endif
                            <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2.5 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gallery Images</label>
                            @if($project->gallery && count($project->gallery) > 0)
                                <div class="grid grid-cols-4 gap-2 mb-2">
                                    @foreach($project->gallery as $image)
                                        @if(is_string($image))
                                        <img src="{{ route('file.show', ['path' => $image]) }}" class="w-full h-16 object-cover rounded">
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="gallery[]" accept="image/*" multiple class="w-full px-4 py-2.5 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Add more images to gallery</p>
                        </div>
                    </div>
                </div>
                
                <!-- Options -->
                <div class="bg-white rounded-xl p-6 border">
                    <h3 class="font-semibold text-gray-900 mb-4">Options</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Featured Project</span>
                        </label>
                        
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->is_active) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">Active (visible on website)</span>
                        </label>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="bg-white rounded-xl p-6 border">
                    <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="ri-save-line mr-1"></i> Update Project
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