<?php

namespace App\Http\Requests\Admin\Product;

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

             'category_id' => 'required',
             'store_id' => 'required',
             'brand_id' => 'required',

             'status' => 'required',

            'weight' => 'required',
            'real_price' => 'required',
            //'sale_price' => 'required',
             'sku' => 'required',
            'qty' => 'required',
            // 'banner_image' => 'dimensions:width=1500,height=450',
            // 'feature_image' => 'dimensions:width=800,height=850'


        ];
    }
    public function messages()
 {
     return [
        //    'banner_image.dimensions' => 'Banner Image must be width=1500px and height=450px',
        //    'feature_image.dimensions' => 'Feature Image must be width=800px and height=850px',

     ];
 }
}
