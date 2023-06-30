<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Products\ProductStoreResource;
use App\Http\Resources\Products\ProductVariationsResource;
use App\Http\Resources\Products\ProductGalleryResource;
use App\Http\Resources\Products\ProductAttributesResource;
use App\Http\Resources\Products\TagsResource;
use App\Http\Resources\Products\ProductDescriptionImageResource;
use App\Http\Resources\Products\BackendTagsResource;
use App\Http\Resources\Products\BrandsResource;

use App\Models\VariationAttribute;

class LitterhubProductResource extends JsonResource
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
            'name' => $this->productName,
            'sku' => $this->sku,
            'scented' => $this->scented,
            'clumping' => $this->clumping,
            'cat_count' => $this->cat_count,
            'litter_material' => json_decode($this->litter_material),
            'type' => $this->type,
            'feature_image' => $this->feature_image,
            'status' => $this->status,
            'weight' => $this->weight,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'real_price' => $this->real_price,
            'sale_price' => $this->sale_price,
            'feature_image' => $this->feature_image,

            'store' => new ProductStoreResource($this->store),
            'variations' => ProductVariationsResource::collection($this->productVariation),
            'gallary' =>  ProductGalleryResource::collection($this->productGallery),
            'description_image' =>  ProductDescriptionImageResource::collection($this->productDescriptionImage) ,
            'experiential_page_image' =>  ProductFeaturePageImageResource::collection($this->productFeaturePageImage) ,
            'attributes' => ProductAttributesResource::collection($this->variationAttributesValue),
            'tags'=>$this->tags?TagsResource::collection($this->tags):null,
            'backend_tags'=>$this->backendtags?BackendTagsResource::collection($this->backendtags):null,
            'brand_tags'=>$this->brands?BrandsResource::collection($this->brands):null,
            'variation_attributes'=>  $this->getAttributeByProduct($this->id)

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
