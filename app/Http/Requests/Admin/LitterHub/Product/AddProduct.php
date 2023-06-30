<?php

namespace App\Http\Requests\Admin\Litterhub\Product;

use Illuminate\Foundation\Http\FormRequest;

class AddProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'productName' => 'required',
   
            'store_id' => 'required',
            'litter_material' => 'required',

            'real_price' => 'required',

            'sku' => 'required',
            'qty' => 'required',
            // 'pet_type' => 'required',
            // 'age' => 'required',
            // 'food_type' => 'required',
            // 'protein_type' => 'required',

            // 'feature_image' => 'dimensions:width=619,height=577',
            // 'image' => 'dimensions:width=619,height=577',
            // 'description_images' => 'dimensions:width=1238,height=652',
            // 'feature_page_images' => 'dimensions:width=1238,height=652',





        ];
    }
    public function messages()
    {
        return [
            //   'description_images.dimensions' => 'Description Image must be width=1238px and height=652px',
              'feature_image.dimensions' => 'Feature Image must be width=619px and height=577px',
            //   'image.dimensions' => 'Image must be width=619px and height=577px',
            //   'feature_page_images.dimensions' => 'Experiential Page Image must be width=1238px and height=652px',



        ];
    }
}
