<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'type' => $this->type,
            'color' => $this->color,
            'description' => $this->description,
            'order' => $this->order,
            'tag_line' => $this->tag_line,


            'status' => $this->status,
            'feature_image' => $this->feature_image,
            'status' => $this->status,
            'category' => self::collection($this->childrens)

        ];
    }
}
