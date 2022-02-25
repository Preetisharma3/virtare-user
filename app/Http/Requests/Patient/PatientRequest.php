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
            'firstName' => 'required',
            'lastName' => 'required',
            'dob' => 'required',
            'phoneNumber' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Patient Email must be required',
            'email.unique' => 'Patient Email must be unique',
            'firstName.required' => 'Patient firstName must be required',
            'lastName.required' => 'Patient lastName must be required',
            'dob.required' => 'Patient Date Of Birth must be required',
            'phoneNumber.required' => 'Patient phoneNumber must be required',
        ];
    }

}
