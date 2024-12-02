<?php

namespace App\Http\Resources\Admin\ProductReview;

use App\Http\Resources\Admin\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [

            'id' => $this->id,
            'user_id' => $this->user_id,
           'product_id' => $this->reviewable_id,
            'product' => new ProductResource($this->whenLoaded('reviewable')),
            "comment" => $this->comment,
            "rate" => $this->rate,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_name' => $this->user->name,
            'user_image_url' => $this->user->image_url,

        ];
    }
}
