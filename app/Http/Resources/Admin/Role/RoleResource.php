<?php

namespace App\Http\Resources\Admin\Role;

use App\Http\Resources\Admin\Permission\PermissionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
