<?php

namespace App\Http\Resources\Carts;

use Illuminate\Http\Resources\Json\JsonResource;

class VariationCartResource extends JsonResource
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
            'real_price' => $this->real_price,
            'sale_price' => $this->sale_price,
            'image' => $this->image,
            'variation_attributes_name_id' => json_decode($this->variation_attributes_name_id),
            'sku' => $this->sku,                               
        ];
    }


    
}
