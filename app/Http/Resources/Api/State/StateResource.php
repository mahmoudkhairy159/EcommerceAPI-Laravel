<?php

namespaceApp\Http\Resources\Api\State;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'code' => $this->code,
            "name" => $this->name,
        ];
    }
}
