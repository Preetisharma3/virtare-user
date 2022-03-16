<?php

namespace App\Http\Requests\Family;

use App\Models\Patient\PatientFamilyMember;
use Urameshibr\Requests\FormRequest;

class FamilyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
            return [
                'email' => 'required',
                'fullName' => 'required',
                'phoneNumber' => 'required',
                'contactType' => 'required',
                'gender' => 'required',
                'relation' => 'required',
                'vitalAuthorization' => 'required',
                'messageAuthorization' => 'required',
            ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Patient Email must be required',
            'name.required' => 'Name must be required',
            'designation.required' => 'Designation must be required',
            'phoneNumber.required' => 'Phone Number must be required',
        ];
    }
}
