<?php

namespace App\Http\Resources\Vendor\City;

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
            "name" => $this->name,

        ];
    }
}
