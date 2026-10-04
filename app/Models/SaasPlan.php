<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'price',
        'description',
        'max_customers',
        'max_routers',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_customers' => 'integer',
            'max_routers' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getYearlyPriceAttribute(): float
    {
        return round((float) $this->price * 12 * 0.8);
    }

    public function getMonthlyEquivalentYearlyAttribute(): float
    {
        return round((float) $this->price * 0.8);
    }

    public function getYearlySavingsAttribute(): float
    {
        return round((float) $this->price * 12 * 0.2);
    }
}
