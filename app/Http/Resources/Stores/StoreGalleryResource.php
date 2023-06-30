<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreGalleryResource extends JsonResource
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
            'image_path' =>$this->image_path
                               
        ];
    }
}
