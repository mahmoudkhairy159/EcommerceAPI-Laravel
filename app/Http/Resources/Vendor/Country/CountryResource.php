<?php

namespace App\Resources\Vendor\Country;

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

        ];
    }
}
