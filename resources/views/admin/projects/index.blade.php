@extends('layouts.admin')

@section('title', 'Projects Management')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Projects Management</h1>
            <p class="text-gray-600 mt-1">Manage your real estate projects and developments</p>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="ri-add-line mr-1"></i> Add Project
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Total Projects</p>
            <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Ongoing</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['ongoing'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Completed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
            <p class="text-gray-500 text-sm">Upcoming</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['upcoming'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 border mb-6">
        <form method="GET" class="grid grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg text-center">Reset</a>
        </form>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $project->featured_image_url }}" class="w-12 h-12 rounded-lg object-cover">
                            <div>
                                <p class="font-medium">{{ $project->title }}</p>
                                <p class="text-sm text-gray-500">{{ Str::limit($project->short_description, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $project->location }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $project->status_badge[0] }}">{{ $project->status_badge[1] }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $project->starting_price ? 'ETB ' . number_format($project->starting_price) : '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $project->views }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('projects.show', $project->slug) }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600"><i class="ri-eye-line"></i></a>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="p-2 text-gray-500 hover:text-green-600"><i class="ri-edit-line"></i></a>
                        <button onclick="deleteProject({{ $project->id }})" class="p-2 text-gray-500 hover:text-red-600"><i class="ri-delete-bin-line"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No projects found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">{{ $projects->links() }}</div>
</div>

<script>
    function deleteProject(id) {
        if (!confirm('Delete this project?')) return;
        fetch(`/admin/projects/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
    }
</script>
@endsection