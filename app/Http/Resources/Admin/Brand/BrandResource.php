<?php

namespace App\Http\Resources\Admin\Brand;

use App\Http\Resources\Admin\BrandImage\BrandImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'long_description_status' => $this->long_description_status,
            'brief' => $this->brief,
            'code' => $this->code,
            "image_url" => $this->image_url,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'serial' => $this->serial,
            'brand_images' => BrandImageResource::collection($this->whenLoaded('brandImages')),

        ];
    }
}
