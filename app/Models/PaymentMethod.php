<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class PaymentMethod extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'type',
        'provider',
        'name',
        'account_number',
        'account_name',
        'qr_code_image',
        'credentials',
        'encrypted_credentials',
        'instructions',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function setCredentialsAttribute(array|string|null $value): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->attributes['encrypted_credentials'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getCredentialsAttribute(): ?array
    {
        if (!$this->encrypted_credentials) {
            return null;
        }
        try {
            $decrypted = Crypt::decryptString($this->encrypted_credentials);
            return json_decode($decrypted, true);
        } catch (\Exception) {
            $json = json_decode($this->encrypted_credentials, true);
            return is_array($json) ? $json : null;
        }
    }

    public function isGateway(): bool
    {
        return $this->type === 'GATEWAY';
    }

    public function isManual(): bool
    {
        return $this->type === 'MANUAL';
    }
}
