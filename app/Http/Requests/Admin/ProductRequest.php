<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product' => 'required|array|min:1',
            'product.*.photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'product.*.name' => 'required',
            'product.*.price' => 'required',
            'product.*.offer_price' => 'required',
            'product.*.stock' => 'required',
            'product.*.category_id' => 'required',
            'product.*.brand_id' => 'required',
            'product.*.summary' => 'nullable',
            'product.*.description' => 'required',
            'product.*.translation_lang' => 'required',
        ];
    }
}
