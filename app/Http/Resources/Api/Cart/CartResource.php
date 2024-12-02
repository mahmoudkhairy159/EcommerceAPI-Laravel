<?php

namespace App\Http\Resources\Api\Cart;

use App\Http\Resources\Api\CartProduct\CartProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_price' => $this->items()->count() == 0 ? $this->items->sum(function ($cartProduct) {
                return $cartProduct->product->selling_price * $cartProduct->quantity;
            }) : 0,
            // 'discount_amount' => $this->discount_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => CartProductResource::collection($this->whenLoaded('products')),

        ];}
}
