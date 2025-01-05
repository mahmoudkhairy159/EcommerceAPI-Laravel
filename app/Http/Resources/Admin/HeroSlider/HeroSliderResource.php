<?php

namespace App\Http\Resources\Admin\HeroSlider;

use Illuminate\Http\Resources\Json\JsonResource;

class HeroSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'paragraph' => $this->paragraph,
            'rank' => $this->rank,
            'button_url' => $this->button_url,
            'status' => $this->status,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
        ];
    }
}
