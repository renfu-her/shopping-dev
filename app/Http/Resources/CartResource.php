<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $items = CartItemResource::collection($this->whenLoaded('items'));
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Calculate discount if coupon is applied
        $discount = 0;
        if ($this->coupon_id && $this->coupon_data) {
            $couponData = $this->coupon_data;
            if ($couponData['type'] === 'percentage') {
                $discount = ($subtotal * $couponData['value']) / 100;
            } elseif ($couponData['type'] === 'fixed') {
                $discount = $couponData['value'];
            }
        }

        $total = $subtotal - $discount;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'items' => $items,
            'items_count' => $this->items->sum('quantity'),
            'subtotal' => $subtotal,
            'formatted_subtotal' => '$' . number_format($subtotal, 2),
            'discount' => $discount,
            'formatted_discount' => '$' . number_format($discount, 2),
            'total' => $total,
            'formatted_total' => '$' . number_format($total, 2),
            'coupon' => $this->when($this->coupon_id, [
                'id' => $this->coupon_id,
                'code' => $this->coupon_data['code'] ?? null,
                'type' => $this->coupon_data['type'] ?? null,
                'value' => $this->coupon_data['value'] ?? null,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
