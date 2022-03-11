<?php

namespace App\Http\Requests\Staff;

use Urameshibr\Requests\FormRequest;

class StaffRequest extends FormRequest
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
            'designationId' => 'required',
            'genderId' => 'required',
            'phoneNumber' => 'required|numeric',
            'specializationId' => 'required',
            'networkId' => 'required'
        ];
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
