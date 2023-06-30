<?php

namespace App\Http\Resources\Coupon;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code' => $this->code,
            'type' => $this->type,
            'apply_to' => $this->apply_to,
            'count' => $this->count,
            'lifetime_coupon' => $this->lifetime_coupon,


            'started_at' => $this->started_at,

            'expired_at' => $this->expired_at,



            'category_id' => json_decode($this->category_id) ?? [],
            'product_type' => $this->product_type,
            'product_id' => json_decode($this->product_id) ??  [],
            'value' => $this->value,



        ];
    }
}
