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
        $id=request()->segment(4);
        if(!empty($id)){
            $patient = PatientFamilyMember::where('udid',$id)->first();
            return [
                'email' => 'required|unique:users,email,'.$patient['userId'].'udid',
                'fullName' => 'required',
                'phoneNumber' => 'required',
                'contactType' => 'required',
                'gender' => 'required',
                'relation' => 'required',
                'vitalAuthorization' => 'required',
                'messageAuthorization' => 'required',
            ];
        }else{
            return [
                'email' => 'required|unique:users,email',
                'fullName' => 'required',
                'phoneNumber' => 'required',
                'contactType' => 'required',
                'gender' => 'required',
                'relation' => 'required',
                'vitalAuthorization' => 'required',
                'messageAuthorization' => 'required',
            ];
        }
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
