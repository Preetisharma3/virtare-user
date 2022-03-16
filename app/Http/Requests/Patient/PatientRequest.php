<?php

namespace App\Http\Requests\Patient;

use App\Models\User\User;
use App\Models\Patient\Patient;
use Urameshibr\Requests\FormRequest;
use App\Models\Patient\PatientFamilyMember;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $patient_udid = request()->segment(2);
        if (!empty($patient_udid)) {
            $patient = Patient::where('udid', $patient_udid)->first();
            $family=User::where([['email',request()->familyEmail],['roleId',6]])->first();
            if($family){
                return [
                    'email' => 'required|unique:users,email,' . $patient['userId'] . 'udid',
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'dob' => 'required',
                    'phoneNumber' => 'required',
                ];
            }else{
                return [
                    'email' => 'required|unique:users,email,' . $patient['userId'] . 'udid',
                    'familyEmail' => 'required|unique:users,email',
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'dob' => 'required',
                    'phoneNumber' => 'required',
                ];
            }
            
        } else {
            return [
                'email' => 'required|unique:users,email',
                'firstName' => 'required',
                'lastName' => 'required',
                'dob' => 'required',
                'phoneNumber' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'email.required' => 'Patient Email must be required',
            'email.unique' => 'Patient Email must be unique',
            'familyEmail.unique' => 'Family Email must be unique',
            'firstName.required' => 'Patient firstName must be required',
            'lastName.required' => 'Patient lastName must be required',
            'dob.required' => 'Patient Date Of Birth must be required',
            'phoneNumber.required' => 'Patient phoneNumber must be required',
        ];
    }
}
