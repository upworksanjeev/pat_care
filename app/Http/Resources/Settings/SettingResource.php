<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
           
            'site_title' => $this->site_title,
            'site_email' => $this->site_email,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'facebook' => $this->facebook,
            'youtube'=>$this->youtube,
            'insta'=>$this->insta,
            'linkedin'=>$this->linkedin,
            'copyright'=>$this->copyright
                               
        ];
    }
}
