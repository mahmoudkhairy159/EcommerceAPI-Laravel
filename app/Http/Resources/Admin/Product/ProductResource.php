<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\ProductImage\ProductImageResource;
use App\Http\Resources\Admin\Service\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'image_url' => $this->image_url,
            'video_url' => $this->video_url,

            'serial' => $this->serial,
            'status' => $this->status,
            'is_featured' => $this->is_featured,

            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'selling_price' => $this->selling_price,
            'cost_price' => $this->cost_price,
            'discount' => $this->discount,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'alert_stock_quantity' => $this->alert_stock_quantity,
            'order_type' => $this->order_type,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'return_policy' => $this->return_policy,
            'rate' => $this->rate,
            'category_id' => $this->category_id,
            'category' => $this->category ? $this->category->name : null,
            'brand_id' => $this->brand_id,
            'brand' => $this->brand ? $this->brand->name : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'product_images' => ProductImageResource::collection($this->whenLoaded('productImages')),
            'related_products' => ProductResource::collection($this->whenLoaded('relatedProducts')),
            'accessories' => ProductResource::collection($this->whenLoaded('accessories')),
        ];
    }
}
