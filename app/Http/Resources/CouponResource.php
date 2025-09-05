<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'formatted_value' => $this->type === 'percentage' ? $this->value . '%' : '$' . number_format($this->value, 2),
            'minimum_amount' => $this->minimum_amount,
            'formatted_minimum_amount' => $this->minimum_amount ? '$' . number_format($this->minimum_amount, 2) : null,
            'maximum_discount' => $this->maximum_discount,
            'formatted_maximum_discount' => $this->maximum_discount ? '$' . number_format($this->maximum_discount, 2) : null,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'remaining_uses' => $this->usage_limit ? max(0, $this->usage_limit - $this->used_count) : null,
            'valid_from' => $this->valid_from,
            'valid_until' => $this->valid_until,
            'is_valid' => $this->is_valid,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
