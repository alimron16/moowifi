<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Router extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'connection_type',
        'host',
        'port',
        'username',
        'encrypted_password',
        'vpn_user',
        'encrypted_vpn_password',
        'tunnel_ip',
        'use_ssl',
        'status',
        'last_seen_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'use_ssl' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['encrypted_password'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getDecryptedPasswordAttribute(): ?string
    {
        return $this->encrypted_password ? Crypt::decryptString($this->encrypted_password) : null;
    }

    public function setVpnPasswordAttribute(?string $value): void
    {
        $this->attributes['encrypted_vpn_password'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getDecryptedVpnPasswordAttribute(): ?string
    {
        return $this->encrypted_vpn_password ? Crypt::decryptString($this->encrypted_vpn_password) : null;
    }

    public function isOnline(): bool
    {
        return $this->status === 'ONLINE';
    }
}
