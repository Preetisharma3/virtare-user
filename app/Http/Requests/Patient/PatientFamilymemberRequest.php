<?php

namespace App\Http\Requests\Patient;

use App\Models\User\User;
use App\Models\Patient\Patient;
use Urameshibr\Requests\FormRequest;

class PatientFamilymemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $family = User::where([['email', request()->familyEmail], ['roleId', 6]])->first();
        if (!$family) {
            return [
                'familyEmail' => 'required|unique:users,email',
                'fullName' => 'required',
                'familyGender' => 'required',
                'relation' => 'required',
            ];
        } else {
            return [
                'fullName' => 'required',
                'familyGender' => 'required',
                'relation' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'familyEmail.unique' => 'Family Email must be unique',
        ];
    }
}
