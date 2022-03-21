<?php

namespace App\Http\Requests\Patient;

use App\Models\Patient\PatientPhysician;
use Urameshibr\Requests\FormRequest;

class PatientPhysicianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id=request()->segment(4);
        if(!empty($id)){
            $patient = PatientPhysician::where('udid',$id)->first();
            return [
                'email' => 'required|unique:users,email,'.$patient['userId'].'udid',
                'name' => 'required',
                'designation' => 'required',
            ];
        }else{
            return [
                'email' => 'required|unique:users,email',
                'name' => 'required',
                'designation' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'email.unique' => 'Patient Email must be unique',
            'email.required' => 'Patient Email must be required',
            'name.required' => 'Name must be required',
            'designation.required' => 'Designation must be required',
        ];
    }
}
