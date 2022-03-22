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
                    if ($request->all) {
                        $data = Staff::whereHas('patientStaff', function ($query) {
                            $query->where('patientId', auth()->user()->patient->id);
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->get();
                        return fractal()->collection($data)->transformWith(new StaffTransformer(true))->toArray();
                    } else {
                        $data = Staff::whereHas('patientStaff', function ($query) {
                            $query->where('patientId', auth()->user()->patient->id);
                        })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->paginate(env('PER_PAGE', 20));
                        return fractal()->collection($data)->transformWith(new StaffTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                    }
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
                    if ($request->all) {
                        $data = PatientPhysician::where([['patientId', auth()->user()->patient->id]])->get();
                        return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->toArray();
                    } else {
                        $data = PatientPhysician::where([['patientId', auth()->user()->patient->id]])->paginate(env('PER_PAGE', 20));
                        return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                    }
                } else {
                    $data = PatientPhysician::where([['patientId', auth()->user()->patient->id], ['udid', $id]])->first();
                    if (!empty($data)) {
                        return fractal()->item($data)->transformWith(new PhysicianTransformer(true))->toArray();
                    } else {
                        return response()->json(['message' => trans('messages.not_found')], 404);
                    }
                }
            } elseif ($type == 'familyMember') {
                if (auth()->user()->roleId == 6) {
                    if (!$id) {
                        if ($request->all) {
                            $data = PatientFamilyMember::with('roles')->orderBy('fullName', 'ASC')->get();
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            $data = PatientFamilyMember::with('roles')->orderBy('fullName', 'ASC')->paginate(env('PER_PAGE', 20));
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                        }
                    } else {
                        $data = PatientFamilyMember::with('roles')->where('udid', $id)->first();
                        if (!empty($data)) {
                            return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            return response()->json(['message' => trans('messages.not_found')], 404);
                        }
                    }
                } elseif (auth()->user()->roleId == 4) {
                    if (!$id) {
                        if ($request->all) {
                            $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id]])->orderBy('fullname', 'ASC')->get();
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id]])->orderBy('fullname', 'ASC')->paginate(env('PER_PAGE', 20));
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                        }
                    } else {
                        $data = PatientFamilyMember::with('roles')->where([['patientId', auth()->user()->patient->id], ['udid', $id]])->first();
                        if (!empty($data)) {
                            return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            return response()->json(['message' => trans('messages.not_found')], 404);
                        }
                    }
                }
            }
        } elseif ($patientId) {
            $patient = Helper::entity('patient', $patientId);
            $notAccess = Helper::haveAccess($patient);
            if (!$notAccess) {
                if ($type == 'staff') {
                    if (!$id) {
                        if ($request->all) {
                            $data = Staff::whereHas('patientStaff', function ($query) use ($patient) {
                                $query->where('patientId', $patient);
                            })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->get();
                            return fractal()->collection($data)->transformWith(new StaffTransformer(true))->toArray();
                        } else {
                            $data = Staff::whereHas('patientStaff', function ($query) use ($patient) {
                                $query->where('patientId', $patient);
                            })->orderBy('firstName', 'ASC')->orderBy('lastName', 'ASC')->paginate(env('PER_PAGE', 20));
                            return fractal()->collection($data)->transformWith(new StaffTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                        }
                    } else {
                        $data = Staff::whereHas('patientStaff', function ($query) use ($patient) {
                            $query->where('patientId', $patient);
                        })->first();
                        if (!empty($data)) {
                            return fractal()->item($data)->transformWith(new StaffTransformer(true))->toArray();
                        } else {
                            return response()->json(['message' => trans('messages.not_found')], 404);
                        }
                    }
                } elseif ($type == 'physician') {
                    if (!$id) {
                        if ($request->all) {
                            $data = PatientPhysician::where([['patientId', $patient]])->get();
                            return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->toArray();
                        } else {
                            $data = PatientPhysician::where([['patientId', $patient]])->paginate(env('PER_PAGE', 20));
                            return fractal()->collection($data)->transformWith(new PhysicianTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                        }
                    } else {
                        $data = PatientPhysician::where([['patientId', $patient], ['udid', $id]])->first();
                        if (!empty($data)) {
                            return fractal()->item($data)->transformWith(new PhysicianTransformer(true))->toArray();
                        } else {
                            return response()->json(['message' => trans('messages.not_found')], 404);
                        }
                    }
                } elseif ($type == 'familyMember') {
                    if (!$id) {
                        if ($request->all) {
                            $data = PatientFamilyMember::with('roles')->where([['patientId', $patient]])->orderBy('fullName', 'ASC')->get();
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            $data = PatientFamilyMember::with('roles')->where([['patientId', $patient]])->orderBy('fullName', 'ASC')->paginate(env('PER_PAGE', 20));
                            return fractal()->collection($data)->transformWith(new PatientFamilyMemberTransformer(true))->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
                        }
                    } else {
                        $data = PatientFamilyMember::with('roles')->where([['patientId', $patient], ['udid', $id]])->first();
                        if (!empty($data)) {
                            return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
                        } else {
                            return response()->json(['message' => trans('messages.not_found')], 404);
                        }
                    }
                }
            }else{
                return $notAccess;
            }
        }
    }
}
