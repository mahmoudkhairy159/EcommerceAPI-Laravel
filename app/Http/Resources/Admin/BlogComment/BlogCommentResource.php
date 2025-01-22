<?php

namespace App\Http\Resources\Admin\BlogComment;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'blog_id' => $this->blog_id,
            'content' => $this->content,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'image_url' => $this->user->image_url
            ],
            'created_at' => $this->created_at,
        ];
    }
}
