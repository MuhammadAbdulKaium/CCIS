<?php

namespace Modules\Canteen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CanteenStockInRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required',
            'categoryId' => 'required',
            'menuId' => 'required',
            'qty' => 'required',
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
