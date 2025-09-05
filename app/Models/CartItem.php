<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'product_data',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'product_data' => 'array',
    ];

    // Relationships
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors & Mutators
    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    public function getProductNameAttribute(): string
    {
        return $this->product_data['name'] ?? $this->product->name ?? 'Unknown Product';
    }

    public function getProductImageAttribute(): ?string
    {
        return $this->product_data['image'] ?? $this->product->image ?? null;
    }

    public function getProductSlugAttribute(): string
    {
        return $this->product_data['slug'] ?? $this->product->slug ?? '';
    }
}
