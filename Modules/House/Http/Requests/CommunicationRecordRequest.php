<?php

namespace Modules\House\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunicationRecordRequest extends FormRequest
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
            "studentId" => 'required',
            "yearId" => 'required',
            "mode" => 'required',
            "date" => "required",
            "fromTime" => 'required',
            "toTime" => 'required',
            "communicationTopics" => 'required'
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
