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

    public function isExpired(): bool
    {
        $currentSub = $this->currentSubscription;

        // If tenant has an ACTIVE paid subscription
        if ($currentSub && $currentSub->status === 'ACTIVE') {
            if ($currentSub->ends_at && $currentSub->ends_at->isPast()) {
                return true;
            }
            return false;
        }

        // If tenant is on trial
        if ($this->trial_ends_at) {
            return $this->trial_ends_at->isPast();
        }

        return false;
    }

    public function isTrial(): bool
    {
        $currentSub = $this->currentSubscription;
        if ($currentSub && $currentSub->status === 'ACTIVE' && (!$currentSub->ends_at || $currentSub->ends_at->isFuture())) {
            return false;
        }

        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
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

    public function getMaxWhatsappGateways(): int
    {
        $plan = $this->activePlan();
        if ($plan && (str_contains(strtoupper($plan->code ?? ''), 'ENTERPRISE') || str_contains(strtoupper($plan->name ?? ''), 'ENTERPRISE'))) {
            return 2;
        }
        return 1;
    }

    public function canAccessRadius(): bool
    {
        return !$this->isExpired();
    }

    public function canAddCustomer(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        $max = $this->getMaxCustomers();
        if ($max === 0) {
            return true; // 0 = unlimited
        }
        return $this->customers()->count() < $max;
    }

    public function canAddRouter(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

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
