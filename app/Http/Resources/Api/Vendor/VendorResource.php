<?php

namespace App\Http\Resources\Api\Vendor;


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
            'image' => $this->image,
            'description' => $this->description,
            'address' => $this->address,
            'social_links' => [
                'facebook' => $this->facebook_link,
                'instagram' => $this->instagram_link,
                'twitter' => $this->twitter_link,
            ],

        ];
    }
}
