<?php

namespace App\Http\Resources\Admin\PageSection;

use Illuminate\Http\Resources\Json\JsonResource;

class PageSectionResource extends JsonResource
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
            'serial' => $this->serial,
            'status' => $this->status, // Cast status to boolean for clarity
            'page_id' => $this->page_id,
        ];
    }
}
