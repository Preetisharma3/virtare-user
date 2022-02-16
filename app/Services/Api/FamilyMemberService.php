<?php

namespace App\Services\Api;

use App\Models\Patient\Patient;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\Family\FamilyPatientTransformer;

class FamilyMemberService
{
    public function listPatient($request,$id){
        $data = PatientFamilyMember::whereHas('patients')->where('userId',auth()->user()->id)->get();
        return fractal()->collection($data)->transformWith(new FamilyPatientTransformer())->toArray();;
    }
}
