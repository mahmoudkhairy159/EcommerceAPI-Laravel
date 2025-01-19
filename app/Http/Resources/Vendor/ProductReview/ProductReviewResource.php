<?php

namespace App\Http\Resources\Api\ProductReview;

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
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'review' => $this->review,
            'rating' => $this->rating,
            'status' => $this->status,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'image_url' => $this->user->image_url
            ] : null,
            'vendor' => $this->vendor ? [
                'id' => $this->vendor->id,
                'name' => $this->vendor->name,
                'image_url' => $this->user->image_url
            ] : null,
            'product' => $this->product ? [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'image_url' => $this->user->image_url
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
