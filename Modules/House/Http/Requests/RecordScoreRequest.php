<?php

namespace Modules\House\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordScoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "houseId" => 'required',
            "yearId" => 'required',
            "termId" => 'required',
            "categoryId" => 'required',
            "batchId" => 'required',
            "date" => "required",
            "score" => 'required'
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
