<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationsResource extends JsonResource
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
            'weight' => $this->weight,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'variation_attributes' => json_decode($this->variation_attributes_name_id)
                               
        ];
    }
}
