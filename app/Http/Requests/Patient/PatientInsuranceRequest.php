<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientInsuranceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'insurance' => 'required_without:insuranceNumber,expirationDate,insuranceName',
            // 'insuranceNumber' => 'required_if:insurance.*',
            // 'expirationDate' => 'required_if:insurance.*',
            // 'insuranceName' => 'required_if:insurance.*',
        ];
    }

    public function messages()
    {
        return [
             'insurance.required_without' => 'Insurance Number must be required','Expiration Date must be required','Insurance Name must be required',
            
            // 'expirationDate.required' => 'Expiration Date must be required',
            // 'insuranceName.required' => 'Insurance Name must be required',
        ];
    }
}
