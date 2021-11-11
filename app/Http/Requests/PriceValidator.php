<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceValidator extends FormRequest
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
            'price' =>  'required|unique:prices|numeric|min:0'
        ];
    }
    public function messages()
    {
        return [
            'price.required' =>  'Bạn chưa nhập giá',
            'price.unique' => 'Giá nhập đã tồn tại',
            'price.numeric' => 'Nhập sai định dạng',
            'price.min' => 'Nhập số lớn hơn 0 '
        ];
    }
}
