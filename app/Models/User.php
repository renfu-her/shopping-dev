<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Shopping Cart Relationships
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function userCoupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class);
    }

    // Helper Methods
    public function getActiveCart(): ?Cart
    {
        return $this->carts()->whereNull('session_id')->first();
    }

    public function getOrCreateCart(): Cart
    {
        $cart = $this->getActiveCart();
        
        if (!$cart) {
            $cart = $this->carts()->create([
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => 0,
            ]);
        }
        
        return $cart;
    }

    public function getDefaultShippingAddress(): ?Address
    {
        return $this->addresses()->shipping()->default()->first();
    }

    public function getDefaultBillingAddress(): ?Address
    {
        return $this->addresses()->billing()->default()->first();
    }

    public function getAvailableCoupons(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userCoupons()
            ->with('coupon')
            ->unused()
            ->get()
            ->pluck('coupon')
            ->filter(fn($coupon) => $coupon && $coupon->isValid());
    }

    public function hasUsedCoupon(Coupon $coupon): bool
    {
        return $this->userCoupons()
            ->where('coupon_id', $coupon->id)
            ->used()
            ->exists();
    }
}
