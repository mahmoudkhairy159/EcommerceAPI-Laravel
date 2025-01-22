<?php

namespace App\Http\Resources\Api\BlogComment;

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
            'replies_count' => $this->replies_count,


            //original comment
            'parent_comment_id' => $this->parent_comment_id,
            'parent_comment' => $this->parent_comment ? [
                'id' =>  $this->parent_comment->id,
                'content' => $this->parent_comment->content,
                'created_at' => $this->parent_comment->created_at,
                'user_id' => $this->parent_comment->user_id, // owner
                'user_name' => $this->parent_comment->user ? $this->parent_comment->user->name : null, // owner
                'user_image_url' => $this->parent_comment->user ? $this->parent_comment->user->image_url : null, // owner
            ] : null,
        ];
    }
}
