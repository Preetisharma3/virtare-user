<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientMedicalRoutineRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'medicine' => 'required',
            'frequency' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'medicine.required' => 'Medicine must be required',
            'frequency.required' => 'Frequency must be required',
            'startDate.required' => 'Start Date must be required',
            'endDate.required' => 'End Date must be required',
        ];
    }
}
