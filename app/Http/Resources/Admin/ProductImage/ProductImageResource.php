<?php

namespace App\Http\Resources\Admin\ProductImage;

use Illuminate\Http\Resources\Json\JsonResource;


class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'serial' => $this->serial,
            'product_id' => $this->product_id,
        ];

    }
}
