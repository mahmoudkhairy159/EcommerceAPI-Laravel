<?php

namespace App\Resources\Admin\Country;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\App\resources\Role\RoleResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'phone_code' => $this->phone_code,
            "name" => $this->name,
            "name_en" => $this->translate('en')->name,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            "status" => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'translations' => $this->getTranslationsArray()
        ];
    }
}
