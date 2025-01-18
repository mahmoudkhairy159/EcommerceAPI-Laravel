<?php

namespace App\Http\Resources\Admin\Wishlist;

use App\Http\Resources\Admin\WishlistProduct\WishlistProductResource;
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
            'total_price' => $this->item->sum(function ($cartProduct) {
                return $cartProduct->product->price * $cartProduct->quantity;
            }),
            // 'discount_amount' => $this->discount_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => WishlistProductResource::collection($this->whenLoaded('items')),

        ];
    }
}
