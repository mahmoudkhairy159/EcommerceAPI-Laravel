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
            'main_category' => $this->main_category,
            'sub_category' => $this->sub_category,
            'category_id' => $this->category_id,
            'category' => $this->category ? $this->category->name : null,
            'code' => $this->code,
            "image_1_url" => $this->image_1_url,
            "image_2_url" => $this->image_2_url,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'rank' => $this->rank,
            'brand_images' => BrandImageResource::collection($this->whenLoaded('brandImages')),

        ];
    }
}
