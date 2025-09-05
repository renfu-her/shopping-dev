<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'subtotal' => $this->subtotal,
            'formatted_subtotal' => '$' . number_format($this->subtotal, 2),
            'tax_amount' => $this->tax_amount,
            'formatted_tax_amount' => '$' . number_format($this->tax_amount, 2),
            'shipping_amount' => $this->shipping_amount,
            'formatted_shipping_amount' => '$' . number_format($this->shipping_amount, 2),
            'discount_amount' => $this->discount_amount,
            'formatted_discount_amount' => '$' . number_format($this->discount_amount, 2),
            'total_amount' => $this->total_amount,
            'formatted_total_amount' => '$' . number_format($this->total_amount, 2),
            'coupon_data' => $this->coupon_data,
            'notes' => $this->notes,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'items_count' => $this->items->sum('quantity'),
            'tracking_number' => $this->tracking_number,
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'cancelled_at' => $this->cancelled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
