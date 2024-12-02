<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\Admin\Product\ProductResource;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tracking_id' => $this->tracking_id,
            'order_date' => $this->order_date,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'discount_amount' => $this->discount_amount,
            'total_price' => $this->total_price,
            'tax' => $this->tax,
            'notes' => $this->notes,
            'order_type' => $this->order_type,
            'state' => $this->state,
            'city' => $this->city,
            'pin_code' => $this->pin_code,
            'billing_address' => $this->billing_address,
            'order_phone_number' => $this->order_phone_number,
            'user' => new UserResource($this->whenLoaded('user')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
