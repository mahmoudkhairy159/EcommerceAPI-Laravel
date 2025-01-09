<?php

namespace App\Http\Resources\Admin\FlashSale;

use App\Http\Resources\Admin\FlashSaleProduct\FlashSaleProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'end_date' => $this->end_date,
            'flash_sale_products' =>  FlashSaleProductResource::collection($this->flashSaleProducts),
        ];
    }
}
