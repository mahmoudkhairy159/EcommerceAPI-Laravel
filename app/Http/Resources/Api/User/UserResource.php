<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\UserProfile\UserProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            "image_url" => $this->image_url,
            'status' => $this->status,
            'active' => $this->active,
            'blocked' => $this->blocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at,
            'profile' => new UserProfileResource($this->profile),
        ];
    }
}
