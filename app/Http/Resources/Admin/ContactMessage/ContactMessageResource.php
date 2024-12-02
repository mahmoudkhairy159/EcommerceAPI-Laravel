<?php

namespace App\Http\Resources\Admin\ContactMessage;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'seen' => $this->seen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
