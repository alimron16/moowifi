<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'package_id',
        'router_id',
        'customer_code',
        'name',
        'phone',
        'email',
        'id_card_number',
        'address',
        'latitude',
        'longitude',
        'connection_type',
        'mikrotik_username',
        'encrypted_mikrotik_password',
        'static_ip',
        'billing_type',
        'billing_cycle_type',
        'billing_day',
        'due_day',
        'grace_period_days',
        'status',
        'auto_cut_enabled',
        'installation_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'billing_day' => 'integer',
            'due_day' => 'integer',
            'grace_period_days' => 'integer',
            'auto_cut_enabled' => 'boolean',
            'installation_date' => 'date',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function setMikrotikPasswordAttribute(?string $value): void
    {
        $this->attributes['encrypted_mikrotik_password'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getDecryptedMikrotikPasswordAttribute(): ?string
    {
        if (! $this->encrypted_mikrotik_password) {
            return null;
        }

        try {
            return Crypt::decryptString($this->encrypted_mikrotik_password);
        } catch (\Throwable) {
            return $this->encrypted_mikrotik_password;
        }
    }

    public function isIsolated(): bool
    {
        return $this->status === 'ISOLATED';
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }
}
