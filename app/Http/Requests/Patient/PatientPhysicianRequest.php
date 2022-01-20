<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientPhysicianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'designation' => 'required',
            'phoneNumber' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Patient Email must be unique',
            'email.required' => 'Patient Email must be required',
            'name.required' => 'Name must be required',
            'designation.required' => 'Designation must be required',
            'phoneNumber.required' => 'Phone Number must be required',
        ];
    }
}
