<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'invoice_number',
        'payment_token',
        'issue_date',
        'due_date',
        'period_start',
        'period_end',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'admin_fee',
        'unique_code',
        'total_amount',
        'paid_amount',
        'status',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'period_start' => 'date',
            'period_end' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'admin_fee' => 'decimal:2',
            'unique_code' => 'integer',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($invoice) {
            if (empty($invoice->payment_token)) {
                $invoice->payment_token = (string) Str::uuid();
            }
            if (empty($invoice->unique_code) || $invoice->unique_code <= 0) {
                $invoice->unique_code = rand(100, 999);
            }
        });
    }

    public function getManualAmountAttribute(): float
    {
        $code = $this->unique_code > 0 ? $this->unique_code : 0;
        return (float) ($this->total_amount + $code);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'OVERDUE' || ($this->status === 'UNPAID' && $this->due_date->isPast());
    }

    public function getPaymentUrlAttribute(): string
    {
        return url('/pay/' . $this->payment_token);
    }
}
