<?php

namespace App\Http\Resources\Admin\Page;

use App\Http\Resources\Admin\Asset\AssetResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'content' =>$this->content,
            'assets' => AssetResource::collection($this->whenLoaded('assets')),
        ];
    }
}
