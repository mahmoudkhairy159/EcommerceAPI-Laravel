<?php

namespace App\Http\Resources\Vendor\Order;

use App\Http\Resources\Vendor\Transaction\TransactionResource;
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
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,
           
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),

            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'sub_total' => $this->sub_total,
            'discount_amount' => $this->discount_amount,
            'amount' => $this->amount,
            'order_address' => $this->order_address,
            'shipping_rule' => $this->shipping_rule,
            'coupon' => $this->coupon,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
