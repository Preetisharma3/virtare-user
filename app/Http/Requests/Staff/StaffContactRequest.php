<?php

namespace App\Http\Requests\Staff;

use App\Models\Staff\Staff;
use Urameshibr\Requests\FormRequest;

class StaffContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $udid = request()->segment(2);
        $staff = Staff::where('udid', $udid)->first();
        return [
            'email' => 'required|unique:staffContacts,email,' . $staff['id'] . 'staffId',
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
