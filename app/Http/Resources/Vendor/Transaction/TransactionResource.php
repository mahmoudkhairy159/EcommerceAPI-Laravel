<?php

namespace App\Http\Resources\Vendor\Transaction;

use App\Http\Resources\Admin\Order\OrderResource;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;


class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Relationships
            'order' => new OrderResource($this->whenLoaded('order')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];

    }
}
