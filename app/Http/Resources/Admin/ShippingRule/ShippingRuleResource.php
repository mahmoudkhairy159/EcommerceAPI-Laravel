<?php

namespace App\Http\Resources\Admin\ShippingRule;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'min_cost' => $this->min_cost,
            'cost' => $this->cost,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
