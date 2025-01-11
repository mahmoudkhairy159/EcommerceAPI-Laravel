<?php

namespace App\Http\Resources\Admin\Coupon;

use App\Http\Resources\Admin\CouponImage\CouponImageResource;
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
            'quantity' => $this->quantity,
            'max_use' => $this->max_use,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date ,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
