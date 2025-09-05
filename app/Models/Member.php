<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'membership_type',
        'membership_status',
        'membership_start_date',
        'membership_end_date',
        'points_balance',
        'total_spent',
        'last_login_at',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'points_balance' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the member's cart.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class, 'member_id');
    }

    /**
     * Get the member's orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id');
    }

    /**
     * Get the member's addresses.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'member_id');
    }

    /**
     * Get the member's coupon usage.
     */
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class, 'member_id');
    }

    /**
     * Get the member's shipping addresses.
     */
    public function shippingAddresses()
    {
        return $this->addresses()->where('type', 'shipping');
    }

    /**
     * Get the member's billing addresses.
     */
    public function billingAddresses()
    {
        return $this->addresses()->where('type', 'billing');
    }

    /**
     * Get the member's default shipping address.
     */
    public function defaultShippingAddress()
    {
        return $this->shippingAddresses()->where('is_default', true)->first();
    }

    /**
     * Get the member's default billing address.
     */
    public function defaultBillingAddress()
    {
        return $this->billingAddresses()->where('is_default', true)->first();
    }

    /**
     * Check if member has active membership.
     */
    public function hasActiveMembership(): bool
    {
        return $this->membership_status === 'active' && 
               $this->membership_end_date && 
               $this->membership_end_date->isFuture();
    }

    /**
     * Get member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get member's full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get member's membership level.
     */
    public function getMembershipLevelAttribute(): string
    {
        if ($this->total_spent >= 10000) {
            return 'platinum';
        } elseif ($this->total_spent >= 5000) {
            return 'gold';
        } elseif ($this->total_spent >= 1000) {
            return 'silver';
        }

        return 'bronze';
    }

    /**
     * Get member's membership benefits.
     */
    public function getMembershipBenefitsAttribute(): array
    {
        $benefits = [
            'bronze' => ['basic_support', 'newsletter'],
            'silver' => ['basic_support', 'newsletter', 'free_shipping', 'early_access'],
            'gold' => ['priority_support', 'newsletter', 'free_shipping', 'early_access', 'exclusive_products'],
            'platinum' => ['vip_support', 'newsletter', 'free_shipping', 'early_access', 'exclusive_products', 'personal_shopper'],
        ];

        return $benefits[$this->membership_level] ?? $benefits['bronze'];
    }

    /**
     * Scope for active members.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for members with active membership.
     */
    public function scopeWithActiveMembership($query)
    {
        return $query->where('membership_status', 'active')
                    ->where('membership_end_date', '>', now());
    }

    /**
     * Scope for members by membership type.
     */
    public function scopeByMembershipType($query, $type)
    {
        return $query->where('membership_type', $type);
    }

    /**
     * Scope for members by membership level.
     */
    public function scopeByMembershipLevel($query, $level)
    {
        $ranges = [
            'bronze' => [0, 999.99],
            'silver' => [1000, 4999.99],
            'gold' => [5000, 9999.99],
            'platinum' => [10000, PHP_FLOAT_MAX],
        ];

        if (isset($ranges[$level])) {
            [$min, $max] = $ranges[$level];
            return $query->whereBetween('total_spent', [$min, $max]);
        }

        return $query;
    }
}
