<?php

namespace App\Http\Resources\Api\ReferenceCustomer;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferenceCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            "image_url" => $this->image_url,

        ];
    }
}
