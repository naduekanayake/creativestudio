<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'phone', 'position', 'is_active', 'avatar', 'dashboard_widgets',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'is_active'          => 'boolean',
        'dashboard_widgets'  => 'array',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'staff'       => 'Staff',
            default       => 'Staff',
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'bg-purple-500/20 text-purple-400',
            'admin'       => 'bg-blue-500/20 text-blue-400',
            'staff'       => 'bg-gray-500/20 text-gray-400',
            default       => 'bg-gray-500/20 text-gray-400',
        };
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}