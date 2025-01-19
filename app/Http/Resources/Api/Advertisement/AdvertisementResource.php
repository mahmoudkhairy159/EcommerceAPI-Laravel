<?php

namespace App\Http\Resources\Api\Advertisement;

use App\Http\Resources\Admin\AdvertisementImage\AdvertisementImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'url' => $this->url,
            'position' => $this->position,
            'clicks' => $this->clicks,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


        ];
    }
}
