<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'image' => $this->image,
            'minimum_stock' => $this->minimum_stock,
            'sale_price' => number_format($this->sale_price,2,",","."),
            'purchase_price' => number_format($this->purchase_price,2,",",".")
        ];
    }
}
