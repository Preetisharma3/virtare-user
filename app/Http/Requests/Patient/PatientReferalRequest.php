<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientReferalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'designation' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required',
            'fax' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name must be required',
            'designation.required' => 'Designation must be required',
            'phoneNumber.required' => 'Phone Number must be required',
            'email.required' => 'Email must be required',
            'fax.required' => 'Fax must be required',
        ];
    }
}
