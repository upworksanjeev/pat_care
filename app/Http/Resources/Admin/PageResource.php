<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'css' => $this->css,
            'feature_image' => $this->feature_image,
            'status' => $this->status,
            'user' => $this->users->name,
            'categories' => $this->categories->name,
            'created' => $this->created_at,
            'updated_at' => $this->updated_at,
      
                               
        ];
    }
}
