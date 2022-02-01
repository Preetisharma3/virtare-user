<?php

namespace App\Services\Api;

use App\Models\Staff\Staff;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Staff\PhysicianTransformer;
use App\Transformers\Staff\FamilyMemberTransformer;
use App\Transformers\Patient\PatientPhysicianTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class TeamService
{
    public function team($request, $type, $id)
    {
        if ($type == 'staff') {
            if (!$id) {
                $data = Staff::where('roleId', 3)->paginate(5);
                return fractal()->collection($data)->transformWith(new StaffTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            } else {
                $data = Staff::where([['roleId', 3], ['id', $id]])->with('user')->first();
                if (!empty($data)) {
                    return fractal()->item($data)->transformWith(new StaffTransformer(true))->toArray();
                } else {
                    return response()->json(['message' => trans('messages.not_found')], 404);
                }
            }
        } elseif ($type == 'physician') {
            if (!$id) {
                $data = PatientPhysician::where([['patientId',auth()->user()->patient->id]])->paginate(5);
                return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            } else {
                $data = PatientPhysician::where([['patientId', auth()->user()->patient->id], ['id', $id]])->first();
                if (!empty($data)) {
                    return fractal()->item($data)->transformWith(new PhysicianTransformer(true))->toArray();
                } else {
                    return response()->json(['message' => trans('messages.not_found')], 404);
                }
            }
        }elseif($type == 'familyMember'){
            if (!$id) {
                $data = PatientFamilyMember::with('roles')->where([['patientId',auth()->user()->patient->id]])->paginate(5);
                return fractal()->collection($data)->transformWith(new FamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            } else {
                $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id], ['id', $id]])->first();
                if (!empty($data)) {
                    return fractal()->item($data)->transformWith(new FamilyMemberTransformer(true))->toArray();
                } else {
                    return response()->json(['message' => trans('messages.not_found')], 404);
                }
            }
        }
    }
}
