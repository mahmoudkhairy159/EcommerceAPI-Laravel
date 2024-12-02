<?php

namespace App\Http\Resources\Api\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'description' => $this->description,
            'main_category' => $this->main_category,
            'code' => $this->code,
            "image_url" => $this->image_url,
            'status' => $this->status,
            'rank' => $this->rank,

        ];
    }
}
