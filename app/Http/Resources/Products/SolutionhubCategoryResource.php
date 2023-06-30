<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;




use App\Models\VariationAttribute;

class SolutionhubCategoryResource extends JsonResource
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
            'id' => $this->problem_id ? $this->problem_id : $this->solution_id,
            'name' => $this->categoryName->name ?? '',
        ];
    }


}
