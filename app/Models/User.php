<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'SUPER_ADMIN' || is_null($this->tenant_id);
    }

    public function isOwner(): bool
    {
        return $this->role === 'OWNER';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['OWNER', 'ADMIN']);
    }

    public function isFinance(): bool
    {
        return in_array($this->role, ['OWNER', 'FINANCE']);
    }

    public function isTechnician(): bool
    {
        return in_array($this->role, ['OWNER', 'TECHNICIAN']);
    }

    /**
     * Check granular module permissions according to AGENTS.md Section 29
     */
    public function canAccessModule(string $module): bool
    {
        if ($this->isSuperAdmin() || $this->role === 'OWNER') {
            return true;
        }

        return match ($this->role) {
            'ADMIN' => in_array($module, ['customers', 'invoices', 'billing', 'packages', 'routers', 'notifications', 'dashboard']),
            'FINANCE' => in_array($module, ['invoices', 'billing', 'payments', 'reports', 'dashboard']),
            'TECHNICIAN' => in_array($module, ['customers', 'routers', 'packages', 'dashboard']),
            default => false,
        };
    }
}
