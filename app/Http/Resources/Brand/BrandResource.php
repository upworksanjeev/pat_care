<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => $this->name,
            'logo' => $this->logo,
            'cover_image' => $this->cover_image,
            'brand_color' => $this->brand_color,
            'tag_line' => $this->tag_line,
            'overview' => $this->overview,
            'category_text' => $this->category_text,
          
        ];
    }
}
