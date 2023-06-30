<?php

namespace App\Http\Resources\Shipping;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipingResource extends JsonResource
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
            'sh_name' => $this->sh_name,
            'sh_address' => $this->sh_address,
            'sh_city' => $this->sh_city,
            'sh_state' => $this->sh_state,
            'sh_country' => $this->sh_country,
            'sh_zip_code' => $this->sh_zip_code,
            'sh_phone' => $this->sh_phone,
            'sh_email' => $this->sh_email,
            'created_at' => $this->created_at,
                   
        ];
    }
}
