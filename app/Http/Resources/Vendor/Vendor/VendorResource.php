<?php

namespace App\Http\Resources\Vendor\Vendor;


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
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'image_url' => $this->image_url,
            'description' => $this->description,
            'address' => $this->address,
            'facebook' => $this->facebook_link,
            'instagram' => $this->instagram_link,
            'twitter' => $this->twitter_link,
            'status' => $this->status,
            'blocked' => $this->blocked,
            'is_featured' => $this->is_featured,
            'serial' => $this->serial,

            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
