<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\Brand\BrandResource;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Http\Resources\Admin\ProductImage\ProductImageResource;
use App\Http\Resources\Admin\ProductVariant\ProductVariantResource;
use App\Http\Resources\Admin\Service\ServiceResource;
use App\Http\Resources\Admin\Vendor\VendorResource;
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
            'seo_description' => $this->seo_description,
            'seo_keys' => $this->seo_keys,
            'image_url' => $this->image_url, // Assuming you've appended this accessor in your model
            'video_url' => $this->video_url,
            'price' => $this->price,
            'offer_price' => $this->offer_price,
            'offer_start_date' => $this->offer_start_date,
            'offer_end_date' => $this->offer_end_date,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'alert_stock_quantity' => $this->alert_stock_quantity,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'return_policy' => $this->return_policy,
            'product_type' => $this->product_type,
            'approval_status' => $this->approval_status,
            'status' => $this->status,
            'serial' => $this->serial,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vendor_id' => $this->vendor_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'product_images' => ProductImageResource::collection($this->whenLoaded('productImages')),
            'product_variants' => ProductVariantResource::collection($this->whenLoaded('productVariants')),
            'related_products' => ProductResource::collection($this->whenLoaded('relatedProducts')),
            'accessories' => ProductResource::collection($this->whenLoaded('accessories')),
        ];
    }
}
