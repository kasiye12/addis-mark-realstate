<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::withCount('properties')
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Role filter
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->admins();
            } elseif ($request->role === 'agent') {
                $query->agents();
            } elseif ($request->role === 'user') {
                $query->users();
            }
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->verified();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => User::count(),
            'admin' => User::admins()->count(),
            'agent' => User::agents()->count(),
            'user' => User::users()->count(),
            'active' => User::active()->verified()->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
            'this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,agent,user',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'email_verified_at' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $validated['role'] === 'admin';
        $validated['email_verified_at'] = $request->boolean('email_verified_at') ? now() : null;
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['properties' => function($query) {
            $query->latest()->take(10);
        }, 'properties.images']);
        
        $stats = [
            'properties_count' => $user->properties()->count(),
            'active_properties' => $user->properties()->where('is_active', true)->count(),
            'featured_properties' => $user->properties()->where('is_featured', true)->count(),
            'total_views' => $user->properties()->sum('views'),
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,agent,user',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $validated['role'] === 'admin';
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account!'
            ], 422);
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own status!'
            ], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'User activated!' : 'User deactivated!'
        ]);
    }

    /**
     * Verify user email.
     */
    public function verify(User $user)
    {
        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User email verified!'
        ]);
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own role!'
            ], 422);
        }

        $request->validate([
            'role' => 'required|in:admin,agent,user',
        ]);

        $user->update([
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully!'
        ]);
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Prevent deleting own account
        if (in_array(auth()->id(), $request->ids)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account!'
            ], 422);
        }

        $users = User::whereIn('id', $request->ids)->get();
        
        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        User::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' users deleted successfully!'
        ]);
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        $query = User::withCount('properties');

        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->admins();
            } elseif ($request->role === 'agent') {
                $query->agents();
            }
        }

        $users = $query->get();
        
        $filename = 'users-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Properties', 'Status', 'Verified', 'Created At']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '-',
                    ucfirst($user->role),
                    $user->properties_count,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}