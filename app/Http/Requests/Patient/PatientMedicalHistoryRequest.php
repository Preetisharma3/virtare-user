<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientConditionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'history' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'history.required' => 'History must be required',
        ];
    }
}
