<?php

namespace App\Http\Requests\Staff;

use App\Models\Staff\Staff;
use Urameshibr\Requests\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $staff_udid = request()->segment(2);

        if(!empty($staff_udid)){
            $staff = Staff::where('udid',$staff_udid)->first();
            return [
                'email' => 'required|unique:users,email,'.$staff['userId'].'udid',
                'firstName' => 'required',
                'lastName' => 'required',
                'designationId' => 'required',
                'genderId' => 'required',
                'phoneNumber' => 'required|numeric',
                'specializationId' => 'required',
                'networkId' => 'required'
            ];
        }else{

            return [
                'email' => 'required|unique:users,email',
                'firstName' => 'required',
                'lastName' => 'required',
                'designationId' => 'required',
                'genderId' => 'required',
                'phoneNumber' => 'required|numeric',
                'specializationId' => 'required',
                'networkId' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'email.required' => 'Staff Email is required',
            'email.unique' => 'This Email Already for Existing Care Co-ordinator, Please add an unique email.',
            'firstName.required' => 'Staff firstName is required',
            'lastName.required' => 'Staff lastName is required',
            'genderId.required' => 'Staff gender is required',
            'phoneNumber.required' => 'Staff Phone Number is required',
            'phoneNumber.numeric' => 'Staff Phone Number is numeric',
            'specializationId.required' =>'Staff specialization is required',
            'networkId.required' => 'Staff network is required',
            'designationId.required' =>'Staff designation id required',
        ];
    }
}
