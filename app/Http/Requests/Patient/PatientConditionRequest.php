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
            'condition' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'condition.required' => 'Condition must be required',
        ];
    }
}
