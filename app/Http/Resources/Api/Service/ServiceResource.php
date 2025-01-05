<?php

namespace App\Http\Resources\Api\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category' => $this->category ? $this->category->name : null,
            'code' => $this->code,
            'rate' => $this->rate,
            "image_url" => $this->image_url,
            'status' => $this->status,
            'serial' => $this->serial,

        ];
    }
}
