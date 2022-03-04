<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use App\Models\Document\Document;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\UserTransformer;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\User\UserPatientTransformer;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class UserService
{
    public function userProfile($request)
    {
        try {
            if (auth()->user()->roleId == 4) {
                $data = User::where('id', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new UserPatientTransformer())->toArray();
            } elseif (auth()->user()->roleId == 6) {
                $data = PatientFamilyMember::where('userId', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new PatientFamilyMemberTransformer())->toArray();
            } else {
                $data = User::where('id', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new UserTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function profile($request)
    {
        try {
            if (auth()->user()->roleId == 4) {
                Patient::where('userId', Auth::user()->id)->update([
                    "nickName" => $request->nickname,
                    "phoneNumber" => $request->contact_no,
                    "updatedBy" => Auth::user()->id,
                ]);
                User::where('id', Auth::user()->id)->update([
                    "profilePhoto" => str_replace(URL::to('/') . '/', "", $request->path),
                ]);
                $user = User::where('udid', Auth::user()->udid)->first();
                return fractal()->item($user)->transformWith(new UserPatientTransformer(true))->toArray();
            } elseif (auth()->user()->roleId == 6) {
                PatientFamilyMember::where('userId', auth()->user()->id)->update([
                    "phoneNumber" => $request->phoneNumber,
                    "contactTypeId" => $request->contactType,
                    "contactTimeId" => $request->contactTime,
                    "updatedBy" => Auth::user()->id,
                ]);
                User::where('id', Auth::user()->id)->update([
                    "profilePhoto" => str_replace(URL::to('/') . '/', "", $request->path),
                ]);
                $user = PatientFamilyMember::where('userId', auth()->user()->id)->first();
                return fractal()->item($user)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
            } else {
                Staff::where('userId', Auth::user()->id)->update([
                    "phoneNumber" => $request->phoneNumber,
                    "updatedBy" => Auth::user()->id,
                ]);
                User::where('id', Auth::user()->id)->update([
                    "profilePhoto" => str_replace(URL::to('/') . '/', "", $request->path),
                ]);
                $user = User::where('udid', Auth::user()->udid)->first();
                return fractal()->item($user)->transformWith(new UserTransformer(true))->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    public function userList($request, $id)
    {
        try {
            $patient=Patient::where('userId',$id)->first();
            if($patient){
                $user= User::where('id', $id)->whereHas('patient', function ($query) use ($id) {
                    $query->where('userId', $id);
                })->first();
                dd($user);
            }else{
                $user= User::where('id', $id)->whereHas('staff', function ($query) use ($id) {
                    $query->where('userId', $id);
                })->first();
            }
            return fractal()->item($user)->transformWith(new UserTransformer(true))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
