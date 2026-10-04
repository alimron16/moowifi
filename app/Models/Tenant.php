<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo_path',
        'status',
        'trial_ends_at',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function routers(): HasMany
    {
        return $this->hasMany(Router::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings, $key, $default);
    }

    public function activePlan(): ?SaasPlan
    {
        return $this->currentSubscription?->saasPlan;
    }

    public function getMaxCustomers(): int
    {
        $plan = $this->activePlan();
        return $plan ? (int) $plan->max_customers : 150;
    }

    public function getMaxRouters(): int
    {
        $plan = $this->activePlan();
        return $plan ? (int) $plan->max_routers : 1;
    }

    public function canAddCustomer(): bool
    {
        $max = $this->getMaxCustomers();
        if ($max === 0) {
            return true; // 0 = unlimited
        }
        return $this->customers()->count() < $max;
    }

    public function canAddRouter(): bool
    {
        $max = $this->getMaxRouters();
        if ($max === 0) {
            return true;
        }
        return $this->routers()->count() < $max;
    }

    public function getRadiusSecret(): string
    {
        return $this->getSetting('radius.secret', 'mwifi_rad_' . strtolower($this->code) . '_' . substr(md5((string) $this->id . 'radius_moowifi_salt'), 0, 8));
    }
}
