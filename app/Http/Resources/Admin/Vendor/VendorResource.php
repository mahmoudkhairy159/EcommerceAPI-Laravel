<?php

namespace App\Http\Resources\Admin\Vendor;


use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            'status' => $this->status,
            'blocked' => $this->blocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            "image_url" => $this->image_url,

        ];
    }
}
