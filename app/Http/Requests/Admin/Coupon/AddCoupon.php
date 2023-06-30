<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class AddCoupon extends FormRequest
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
             'name' => 'required',
             'code' => [
                'required',
                'unique:coupons,code'
                ],
             'type' => 'required',
             'apply_to' => 'required',
             'value' => 'required',
             'count' => 'required',
            //  'expired_at' => 'required',



        ];
    }
}
