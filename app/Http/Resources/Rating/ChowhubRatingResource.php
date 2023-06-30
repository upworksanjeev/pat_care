<?php

namespace App\Http\Resources\Rating;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Products\ChowhubProductResource;

use App\Http\Resources\Users\UserResource;
class ChowhubRatingResource extends JsonResource
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
            'rating' => $this->rating,
            'description' => $this->description,
            'user'=> $this->user_id?new UserResource($this->user): [],
            'product'=>$this->product_id? new ChowhubProductResource($this->product): [],
            'status' => $this->status,
            'created_at' => $this->created_at,


        ];
    }
}
