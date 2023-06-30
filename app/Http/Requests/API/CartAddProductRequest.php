<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CartAddProductRequest extends FormRequest
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
            'key' => 'required',
            'product_id' => 'required',
            'variation_product_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:10'
        ];
    }
}
