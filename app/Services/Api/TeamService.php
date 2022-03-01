<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Staff\Staff;
use App\Models\Patient\PatientPhysician;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Staff\PhysicianTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class TeamService
{
    public function team($request, $patientId, $type, $id)
    {
        if (!$patientId) {
            if ($type == 'staff') {
                if (!$id) {
                    $data = Staff::whereHas('patientStaff', function ($query) {
                        $query->where('patientId', auth()->user()->patient->id);
                    })->paginate(5);
                    return fractal()->collection($data)->transformWith(new StaffTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {
                    $data = Staff::where([['roleId', 3], ['udid', $id]])->with('user')->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new StaffTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            } elseif ($type == 'physician') {
                if (!$id) {
                    $data = PatientPhysician::where([['patientId', auth()->user()->patient->id]])->paginate(5);
                    return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {
                    $data = PatientPhysician::where([['patientId', auth()->user()->patient->id], ['id', $id]])->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new PhysicianTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            } elseif ($type == 'familyMember') {
                if (!$id) {
                    $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id]])->paginate(5);
                    return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {
                    $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id], ['udid', $id]])->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            }
        } elseif ($patientId) {
            $patient=Helper::entity('patient',$patientId);
            if ($type == 'staff') {
                if (!$id) {
                    $data = Staff::whereHas('patientStaff', function ($query) use ($patient) {
                        $query->where('patientId', $patient);
                    })->paginate(5);
                    return fractal()->collection($data)->transformWith(new StaffTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {
                    $data = Staff::where([['roleId', 3], ['udid', $id]])->with('user')->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new StaffTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            } elseif ($type == 'physician') {
                if (!$id) {
                    $data = PatientPhysician::where([['patientId', $patient]])->paginate(5);
                    return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {

                    $data = PatientPhysician::where([['patientId', $patient], ['id', $id]])->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new PhysicianTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            } elseif ($type == 'familyMember') {
                if (!$id) {
                    $data = PatientFamilyMember::with('roles')->where([['patientId', $patient]])->paginate(5);
                    return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                } else {
                    $data = PatientFamilyMember::with('roles')->where([['patientId', $patientId], ['udid', $id]])->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            }
        }
    }
}
