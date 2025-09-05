<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_data',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
    ];

    protected $casts = [
        'coupon_data' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Methods
    public function addItem(Product $product, int $quantity = 1): CartItem
    {
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            $existingItem->update(['price' => $product->current_price]);
            return $existingItem;
        }

        return $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->current_price,
            'product_data' => $product->toArray(),
        ]);
    }

    public function removeItem(Product $product): bool
    {
        return $this->items()->where('product_id', $product->id)->delete() > 0;
    }

    public function updateItemQuantity(Product $product, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($product);
        }

        $item = $this->items()->where('product_id', $product->id)->first();
        if ($item) {
            $item->update(['quantity' => $quantity]);
            return true;
        }

        return false;
    }

    public function clear(): void
    {
        $this->items()->delete();
        $this->update([
            'subtotal' => 0,
            'tax_amount' => 0,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total' => 0,
            'coupon_data' => null,
        ]);
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discountAmount = $this->discount_amount;
        $taxAmount = $this->tax_amount;
        $shippingAmount = $this->shipping_amount;

        $total = $subtotal + $taxAmount + $shippingAmount - $discountAmount;

        $this->update([
            'subtotal' => $subtotal,
            'total' => max(0, $total),
        ]);
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}
