<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'state' => 'required',
            'country' => 'required',
            'sh_email' => 'required|email',
            'sh_name' => 'required',
            'sh_city' => 'required',
            'sh_state' => 'required',
            'sh_address' => 'required',
            'sh_country' => 'required',
            'sh_zip_code' => 'required',
            'sh_phone' => 'required',
            'payment_method' => 'required',
            'shippingmethod' => 'required',
            'remark' => 'required'
            
        ];
    }
}
