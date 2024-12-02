<?php

namespace App\Http\Resources\Api\WishlistItem;

use App\Http\Resources\Api\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'item' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
