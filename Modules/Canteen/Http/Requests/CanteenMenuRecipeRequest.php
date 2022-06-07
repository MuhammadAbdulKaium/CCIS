<?php

namespace Modules\Canteen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CanteenMenuRecipeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menuId' => 'required',
            'recipeName' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
