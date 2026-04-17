<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
        'phone',
        'avatar',
        'bio',
        'email_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'avatar_url',
        'role_badge',
        'initials',
        'status_badge',
    ];

    /*
    |--------------------------------------------------------------------------
    | Role Check Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin === true;
    }

    /**
     * Check if user is agent
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can access agent panel
     */
    public function canAccessAgent(): bool
    {
        return $this->isAdmin() || $this->isAgent();
    }

    /**
     * Check if user can manage properties
     */
    public function canManageProperties(): bool
    {
        return $this->isAdmin() || $this->isAgent();
    }

    /**
     * Check if user can manage all properties (admin only)
     */
    public function canManageAllProperties(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage users (admin only)
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage settings (admin only)
     */
    public function canManageSettings(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage testimonials (admin only)
     */
    public function canManageTestimonials(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage team (admin only)
     */
    public function canManageTeam(): bool
    {
        return $this->isAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get properties created by user
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get inquiries made by user
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            // Use direct file route for cPanel compatibility
            return route('file.show', ['path' => $this->avatar]);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=2563eb&color=fff';
    }

    /**
     * Get initials from name
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get role badge styling
     */
    public function getRoleBadgeAttribute()
    {
        return match($this->role) {
            'admin' => ['bg-purple-100 text-purple-800', 'Admin'],
            'agent' => ['bg-blue-100 text-blue-800', 'Agent'],
            'user' => ['bg-gray-100 text-gray-800', 'User'],
            default => ['bg-gray-100 text-gray-800', 'Unknown'],
        };
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        if (!$this->email_verified_at) {
            return ['bg-yellow-100 text-yellow-800', 'Unverified'];
        }
        
        if (!$this->is_active) {
            return ['bg-red-100 text-red-800', 'Inactive'];
        }
        
        return ['bg-green-100 text-green-800', 'Active'];
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRouteAttribute()
    {
        return match(true) {
            $this->isAdmin() => route('admin.dashboard'),
            $this->isAgent() => route('agent.dashboard'),
            default => route('dashboard'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin')->orWhere('is_admin', true);
    }

    /**
     * Scope a query to only include agents.
     */
    public function scopeAgents($query)
    {
        return $query->where('role', 'agent');
    }

    /**
     * Scope a query to only include regular users.
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope a query to search users.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by role.
     */
    public function scopeRole($query, $role)
    {
        if ($role === 'admin') {
            return $query->admins();
        }
        return $query->where('role', $role);
    }
}