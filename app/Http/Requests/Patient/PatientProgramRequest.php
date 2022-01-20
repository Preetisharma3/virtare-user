<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientProgramRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'program' => 'required',
            'onboardingScheduleDate' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'program.required' => 'Program must be required',
            'onboardingScheduleDate.required' => 'Onboarding Schedule Date must be required',
        ];
    }
}
