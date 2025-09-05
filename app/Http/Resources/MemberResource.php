<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'full_address' => $this->full_address,
            'membership_type' => $this->membership_type,
            'membership_status' => $this->membership_status,
            'membership_level' => $this->membership_level,
            'membership_benefits' => $this->membership_benefits,
            'membership_start_date' => $this->membership_start_date,
            'membership_end_date' => $this->membership_end_date,
            'has_active_membership' => $this->hasActiveMembership(),
            'points_balance' => $this->points_balance,
            'formatted_points_balance' => '$' . number_format($this->points_balance, 2),
            'total_spent' => $this->total_spent,
            'formatted_total_spent' => '$' . number_format($this->total_spent, 2),
            'last_login_at' => $this->last_login_at,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
