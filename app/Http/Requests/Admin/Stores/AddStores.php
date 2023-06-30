<?php

namespace App\Http\Requests\Admin\Stores;

use Illuminate\Foundation\Http\FormRequest;

class AddStores extends FormRequest
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
            'name' => 'required','address' => 'required','city' => 'required','state' => 'required','country' => 'required','zip_code' => 'required'
        ];
    }
}
