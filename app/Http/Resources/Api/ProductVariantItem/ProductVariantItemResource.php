<?php

namespace App\Http\Resources\Api\ProductVariantItem;

use Illuminate\Http\Resources\Json\JsonResource;


class ProductVariantItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'name' => $this->name,
            'price' => $this->price,
            'is_default' => $this->is_default,
            'status' => $this->status,
        ];

    }
}
