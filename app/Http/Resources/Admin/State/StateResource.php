<?php

namespace App\Resources\Admin\State;

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
            "status" => $this->status,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'translations'=>$this->getTranslationsArray()
        ];
    }
}
