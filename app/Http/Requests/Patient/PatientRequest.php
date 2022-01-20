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
            'familyEmail' => 'required|unique:users,email',
            'firstName' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'language' => 'required',
            'phoneNumber' => 'required',
            'contactType' => 'required',
            'contactTime' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipCode' => 'required',
            'appartment' => 'required',
            'address' => 'required',
            'fullName' => 'required',
            'familyPhoneNumber' => 'required',
            'familyContactType' => 'required',
            'familyContactTime' => 'required',
            'familyGender' => 'required',
            'relation' => 'required',
            'emergencyFullName' => 'required',
            'emergencyEmail' => 'required|unique:patientEmergencyContacts,email',
            'emergencyPhoneNumber' => 'required',
            'emergencyContactType' => 'required',
            'emergencyContactTime' => 'required',
            'emergencyGender' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Patient Email must be required',
            'email.unique' => 'Patient Email must be unique',
            'familyEmail.required' => 'Family Member Email must be required',
            'familyEmail.unique' => 'Family Member Email must be unique',
            'firstName.required' => 'Patient firstName must be required',
            'dob.required' => 'Patient Date Of Birth must be required',
            'gender.required' => 'Patient gender must be required',
            'language.required' => 'Patient language must be required',
            'phoneNumber.required' => 'Patient phoneNumber must be required',
            'contactType.required' => 'Patient contactType must be required',
            'contactTime.required' => 'Patient contactTime must be required',
            'country.required' => 'Patient country must be required',
            'state.required' => 'Patient state must be required',
            'city.required' => 'Patient city must be required',
            'zipCode.required' => 'Patient zipCode must be required',
            'appartment.required' => 'Patient appartment must be required',
            'address.required' => 'Patient address must be required',
            'fullName.required' => 'fullName must be required',
            'familyPhoneNumber.required' => 'Family Member Phone Number must be required',
            'familyContactType.required' => 'Family Member Contact Type must be required',
            'familyContactTime.required' => 'Family Member Contact Time must be required',
            'familyGender.required' => 'Family Member Gender must be required',
            'relation.required' => 'Family Member relation must be required',
            'emergencyFullName.required' => 'Emergency Contact FullName must be required',
            'emergencyEmail.required' => 'Emergency Contact Email must be required',
            'emergencyEmail.unique' => 'Emergency Contact Email must be unique',
            'emergencyPhoneNumber.required' => 'Emergency Contact PhoneNumber must be required',
            'emergencyContactType.required' => 'Emergency Contact ContactType must be required',
            'emergencyContactTime.required' => 'Emergency Contact ContactTime must be required',
            'emergencyGender.required' => 'Emergency Contact Gender must be required',
        ];
    }

}
