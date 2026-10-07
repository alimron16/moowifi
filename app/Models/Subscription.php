<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'saas_plan_id',
        'order_number',
        'amount',
        'unique_code',
        'billing_cycle',
        'payment_method',
        'proof_path',
        'status',
        'starts_at',
        'ends_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'unique_code' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($subscription) {
            if (empty($subscription->unique_code) || $subscription->unique_code <= 0) {
                $subscription->unique_code = rand(100, 999);
            }
        });
    }

    public function getManualAmountAttribute(): float
    {
        $code = $this->unique_code > 0 ? $this->unique_code : 0;
        return (float) ($this->amount + $code);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'saas_plan_id');
    }

    public function saasPlan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'saas_plan_id');
    }
}
