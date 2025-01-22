<?php

namespace App\Http\Resources\Admin\Blog;

use App\Http\Resources\Admin\BlogCategory\BlogCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'seo_description' => $this->seo_description,
            'seo_keys' => $this->seo_keys,
            'body' => $this->body,
            'image_url' => $this->image_url,
            'status' => $this->status,
            'categories' => BlogCategoryResource::collection($this->whenLoaded('blogCategories')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
