<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category' => 'required|array|min:1',
            'category.*.photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'category.*.title' => 'required',
            'category.*.description' => 'nullable',
            'category.*.translation_lang' => 'required',
        ];
    }
}
