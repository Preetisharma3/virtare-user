<?php

namespace App\Http\Requests\Family;

use App\Models\User\User;
use Urameshibr\Requests\FormRequest;
use App\Models\Patient\PatientFamilyMember;

class FamilyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $family=User::where([['email',request()->email],['roleId',6]])->first();
        if($family){
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
            'email.required' => 'Patient Email must be required',
            'email.unique' => 'Email must be unique',
            'name.required' => 'Name must be required',
            'designation.required' => 'Designation must be required',
            'phoneNumber.required' => 'Phone Number must be required',
        ];
    }
}
