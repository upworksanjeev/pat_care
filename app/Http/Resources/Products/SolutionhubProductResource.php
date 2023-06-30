<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Products\TagsResource;
use App\Http\Resources\Products\BackendTagsResource;
use App\Http\Resources\Products\BrandsResource;
use App\Http\Resources\Products\SolutionhubCategoryResource;


class SolutionhubProductResource extends JsonResource
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
           
            'status' => $this->status,
            'description' => $this->description,
            'category_id' => $this->category_id,
            
            'category'=>$this->category->name?? '',


           
            'feature_image' => $this->feature_image ?? '',
            'tags'=>$this->tags?TagsResource::collection($this->tags):null,
            'backend_tags'=>$this->backendtags?BackendTagsResource::collection($this->backendtags):null,
            'brand'=>$this->brands?BrandsResource::collection($this->brands):null,
            'solutionCategory'=>$this->solutionCategory?SolutionhubCategoryResource::collection($this->solutionCategory):null,
            'problemCategory'=>$this->problemCategory?SolutionhubCategoryResource::collection($this->problemCategory):null,

        ];
    }


}
