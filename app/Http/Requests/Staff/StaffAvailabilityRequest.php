<?php

namespace App\Http\Requests\Staff;

use App\Models\Staff\Staff;
use Urameshibr\Requests\FormRequest;
use App\Helper;

class StaffAvailabilityRequest extends FormRequest
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
            'startTime' => 'required',
            'endTime' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'startTime.required' => 'Start time is required',
            'endTime.required' => 'End time time is required',
            
            
        ];
    }
}
