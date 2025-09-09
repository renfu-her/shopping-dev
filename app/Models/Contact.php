<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'newsletter_subscription',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'newsletter_subscription' => 'boolean',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'replied' => 'Replied',
            'archived' => 'Archived',
            default => 'Unknown',
        };
    }

    public function getSubjectLabelAttribute(): string
    {
        return match ($this->subject) {
            'general' => 'General Inquiry',
            'support' => 'Customer Support',
            'sales' => 'Sales Question',
            'partnership' => 'Partnership',
            'feedback' => 'Feedback',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    // Methods
    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}
