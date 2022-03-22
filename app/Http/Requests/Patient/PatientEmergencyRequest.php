<?php

namespace App\Http\Requests\Patient;

use App\Models\Patient\PatientEmergencyContact;
use Urameshibr\Requests\FormRequest;

class PatientEmergencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $family = PatientEmergencyContact::where('email', request()->emergencyEmail)->first();
        if ($family) {
            return [
                'emergencyEmail' => 'required|unique:patientEmergencyContacts,email',
                'fullName' => 'required',
                'gender' => 'required',
            ];
        } else {
            return [
                'emergencyEmail' => 'required',
                'fullName' => 'required',
                'gender' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'emergencyEmail.unique' => 'Email must be unique',
            'emergencyEmail.required' => 'Email must be required',
            'fullName.required' => 'FullName must be required',
            'gender.required' => 'Gender must be required',
        ];
    }
}
