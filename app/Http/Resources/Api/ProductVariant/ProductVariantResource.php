<?php

namespace App\Http\Resources\Api\ProductVariant;

use App\Http\Resources\Api\ProductVariantItem\ProductVariantItemResource;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'product_id' => $this->product_id,
            'product_Variant_items' => ProductVariantItemResource::collection($this->productVariantItems),
        ];

    }
}
