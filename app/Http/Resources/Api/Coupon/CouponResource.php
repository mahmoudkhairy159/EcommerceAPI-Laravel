<?php

namespace App\Http\Resources\Api\Coupon;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            // 'quantity' => $this->quantity,
            // 'total_used' => $this->total_used,
            // 'max_use' => $this->max_use,
            // 'start_date' => $this->start_date,
            // 'end_date' => $this->end_date ,
            // 'status' => $this->status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
