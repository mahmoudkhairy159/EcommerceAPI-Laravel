<?php

namespace App\Http\Resources\Api\Wishlist;

use App\Http\Resources\Api\WishlistProduct\WishlistProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'wishlistProducts' => WishlistProductResource::collection($this->whenLoaded('wishlistProducts')),

        ];
    }
}
