<?php

namespace App\Http\Resources\Api\OrderProduct;

use App\Http\Resources\Admin\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->pivot->order_id,
            'product_id' => $this->pivot->product_id,
            'quantity' => $this->pivot->quantity,
            'selling_price' => $this->pivot->selling_price,
            'cost_price' => $this->pivot->cost_price,
            'discount' => $this->pivot->discount,
            'return_policy' => $this->pivot->return_policy,
            'item' => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
