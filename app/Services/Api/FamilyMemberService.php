<?php

namespace App\Services\Api;

use App\Models\Patient\Patient;
use App\Models\Patient\PatientFamilyMember;

class FamilyMemberService
{
    public function listPatient($request,$id){
        $data = Patient::with('familyMember.patient')->where('');
        return $data;
    }
}
