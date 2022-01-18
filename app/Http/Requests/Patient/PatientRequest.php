<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|unique:users,email',
            'familyEmail' => 'required|unique:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Patient Email must be required',
            'email.unique' => 'Patient Email must be unique',
            'familyEmail.required' => 'Family Member Email must be required',
            'familyEmail.unique' => 'Family Member Email must be unique'
        ];
    }

}
