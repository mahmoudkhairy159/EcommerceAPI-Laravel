<?php

namespace App\Http\Resources\Admin\FlashSaleProduct;

use App\Http\Resources\Admin\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'show_at_home' => $this->show_at_home,
            'status' => $this->status,
            'end_date'=>$this->flashSale->end_date,
            'product' => new ProductResource($this->product),



        ];
    }
}
