<?php

namespace App\Http\Resources\Admin\CartProduct;

use App\Http\Resources\Admin\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'tax' => $this->tax,
            'subtotal' => $this->subtotal,
            'options' => $this->options,
            'expires_at' => $this->expires_at,
            'product' => new ProductResource($this->whenLoaded('product')), // Load product details if eager-loaded
        ];
    }
}
