<?php

namespace App\Http\Resources\Carts;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Products\ProductAttributesResource;

class ProductCartResource extends JsonResource
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
            'productName' => $this->productName,
            'sku' => $this->sku,
            'type' => $this->type,
            'store_id'=>$this->store_id,
            'category_id'=>$this->category_id,
            'feature_image'=>$this->feature_image,
            'real_price'=>$this->real_price,
            'sale_price'=>$this->sale_price,
            'attributes' => ProductAttributesResource::collection($this->variationAttributesValue),
                               
        ];
    }

    
}
