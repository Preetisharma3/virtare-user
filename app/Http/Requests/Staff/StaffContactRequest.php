<?php

namespace App\Http\Requests\Staff;

use Urameshibr\Requests\FormRequest;

class StaffContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|unique:staffContacts,email',
            'firstName' => 'required',
            'phoneNumber' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.unique' => 'Email is unique',
            'firstName.required' => 'firstName is required',
            'phoneNumber.required' => 'phoneNumber is required',
            'phoneNumber.numeric' => 'Phone Number is numeric',
        ];
    }
}
