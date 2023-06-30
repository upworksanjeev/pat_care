<?php

namespace App\Http\Resources\Carts;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Carts\ProductCartResource;
use App\Http\Resources\Carts\VariationCartResource;
use App\Http\Resources\Products\AttributesResource;
use App\Models\VariationAttribute;

class CartItemsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
           
            'id' => $this->id,
            'product_id' => $this->product_id,
            'cart_id' => $this->cart_id,
            'quantity' => $this->quantity,
            'discountPrice' => 0,
            'product' => new ProductCartResource($this->product),
            'variationProduct'=>new VariationCartResource($this->variationProduct),
            'variation_attributes'=>  $this->getAttributeByProduct($this->product_id)

                               
        ];
    }

    public function getAttributeByProduct($id)
    {
      $attributes = VariationAttribute::whereHas('variationAttributeName', function ($query) use ($id) {
        return $query->where('product_id', '=', $id);
    })->get();

    return  AttributesResource::collection($attributes);
        }
}
