<?php

namespace App\Resources\Admin\City;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            "name" => $this->name,
            "status" => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'translations'=>$this->getTranslationsArray()
        ];
    }
}
