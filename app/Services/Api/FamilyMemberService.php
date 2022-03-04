<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Family\FamilyPatientTransformer;

class FamilyMemberService
{
    public function listPatient($request, $id)
    {
        if ($id) {
            $patient=Helper::entity('patient',$id);
            $access=Helper::haveAccess($patient);
            if(!$access){
                $data = PatientFamilyMember::whereHas('patients')->where([['patientId', $patient], ['userId', auth()->user()->id]])->first();
                return fractal()->item($data)->transformWith(new FamilyPatientTransformer())->toArray();
            }
        } elseif (!$id) {
            $data = PatientFamilyMember::whereHas('patients')->where('userId', auth()->user()->id)->get();
            return fractal()->collection($data)->transformWith(new FamilyPatientTransformer())->toArray();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
    }
}
