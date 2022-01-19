<?php

namespace App\Http\Requests\Patient;

use Urameshibr\Requests\FormRequest;

class PatientInventoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'deviceType' => 'required',
            'modelNumber' => 'required',
            'serialNumber' => 'required',
            'macAddress' => 'required',
            'deviceTime' => 'required',
            'serverTime' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'deviceType.required' => 'Device Type must be required',
            'modelNumber.required' => 'Model Number must be required',
            'serialNumber.required' => 'Serial Number must be required',
            'macAddress.required' => 'Mac Address must be required',
            'deviceTime.required' => 'Device Time must be required',
            'serverTime.required' => 'Server Time must be required',
        ];
    }
}
