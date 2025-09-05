<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCoupon extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'used_at',
        'discount_amount',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'discount_amount' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    public function scopeUnused($query)
    {
        return $query->whereNull('used_at');
    }

    // Methods
    public function markAsUsed(Order $order, float $discountAmount): void
    {
        $this->update([
            'used_at' => now(),
            'order_id' => $order->id,
            'discount_amount' => $discountAmount,
        ]);

        $this->coupon->incrementUsage();
    }

    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    // Accessors
    public function getFormattedDiscountAmountAttribute(): string
    {
        return '$' . number_format($this->discount_amount, 2);
    }
}
