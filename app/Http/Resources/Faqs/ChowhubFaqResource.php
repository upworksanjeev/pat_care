<?php

namespace App\Http\Resources\Faqs;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Faqs\ChowhubProductResource;
use App\Http\Resources\Users\UserResource;
class ChowhubFaqResource extends JsonResource
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
            'description' => $this->description,
            'user'=> $this->user_id?new UserResource($this->user): [],
            'product'=>$this->product_id? new ChowhubProductResource($this->product): [],
            'published' => $this->published,
            'created_at' => $this->created_at,


        ];
    }
}
