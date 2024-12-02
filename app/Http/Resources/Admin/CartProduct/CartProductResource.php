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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'item' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
