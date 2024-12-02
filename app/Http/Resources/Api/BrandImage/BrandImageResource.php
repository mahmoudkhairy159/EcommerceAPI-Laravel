<?php

namespace App\Http\Resources\Api\BrandImage;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'rank' => $this->rank,
            'brand_id' => $this->brand_id,

        ];

    }
}
