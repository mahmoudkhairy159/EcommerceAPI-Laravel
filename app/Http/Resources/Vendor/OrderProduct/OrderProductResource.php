<?php

namespace App\Http\Resources\Vendor\OrderProduct;

use App\Http\Resources\Vendor\Order\OrderResource;
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
            'order' => new OrderResource($this->whenLoaded('order')),  // Nested OrderResource
            'vendor' => $this->vendor ? [
                'id' => $this->vendor->id,
                'name' => $this->vendor->name,
            ] : null,
            'product' => $this->product ? [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
            ] : null,
            'price' => $this->price,
            'tax' => $this->tax,
            'quantity' => $this->quantity,
            'total_price' => $this->price * $this->quantity,  // Calculated total price
            'variants'=>$this->variants,
            'variantsTotalPrice'=>$this->variantsTotalPrice,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
