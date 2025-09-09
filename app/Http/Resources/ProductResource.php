<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'formatted_price' => $this->formatted_price,
            'formatted_sale_price' => $this->formatted_sale_price,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_level' => $this->min_stock_level,
            'manage_stock' => $this->manage_stock,
            'allow_backorder' => $this->allow_backorder,
            'is_in_stock' => $this->is_in_stock,
            'is_low_stock' => $this->is_low_stock,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'image' => $this->image ? asset($this->image) : null,
            'gallery' => $this->gallery ? collect($this->gallery)->map(fn($image) => asset($image)) : [],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
