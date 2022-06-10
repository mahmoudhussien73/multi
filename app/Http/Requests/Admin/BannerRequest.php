<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'banner' => 'required|array|min:1',
            'banner.*.photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'banner.*.title' => 'required',
            'banner.*.description' => 'nullable',
            'banner.*.translation_lang' => 'required',
        ];
    }
}
