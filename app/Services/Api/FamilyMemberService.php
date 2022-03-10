<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Patient\PatientTransformer;

class FamilyMemberService
{
    public function listPatient($request, $id)
    {
        if ($id) {
            $patient=Helper::entity('patient',$id);
            $access=Helper::haveAccess($patient);
            if(!$access){
                $data = PatientFamilyMember::whereHas('patients')->where([['patientId', $patient], ['userId', auth()->user()->id]])->first();
                return fractal()->item($data->patients)->transformWith(new PatientTransformer(true))->toArray();
            }
        } elseif (!$id) {
            $data=Patient::whereHas('family',function($query){
                $query->where('userId',auth()->user()->id);
            })->orderBy('firstName','ASC')->orderBy('lastName','ASC')->get();
            return fractal()->collection($data)->transformWith(new PatientTransformer(true))->toArray();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
    }
}
