<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Transformers\User\UserTransformer;
use App\Models\Patient\PatientFamilyMember;
use App\Transformers\Patient\PatientTransformer;
use App\Transformers\User\UserPatientTransformer;
use App\Transformers\Patient\PatientFamilyMemberTransformer;

class UserService
{
    public function userProfile($request)
    {
        try {
            if (auth()->user()->roleId == 4) {
                $data = patient::where('userId', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new PatientTransformer(true))->toArray();
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

                $file = array();
                if (!empty($request->path)) {
                    $file['profilePhoto'] = str_replace(str_replace("public", "", URL::to('/') . '/'), "", $request->path);
                }
                User::where('id', Auth::user()->id)->update($file);
                $user = User::where('udid', Auth::user()->udid)->first();
                return fractal()->item($user)->transformWith(new PatientTransformer(false))->toArray();
            } elseif (auth()->user()->roleId == 6) {
                PatientFamilyMember::where('userId', auth()->user()->id)->update([
                    "phoneNumber" => $request->phoneNumber,
                    "contactTypeId" => $request->contactType,
                    "contactTimeId" => $request->contactTime,
                    "updatedBy" => Auth::user()->id,
                ]);

                $file = array();
                if (!empty($request->path)) {
                    $file['profilePhoto'] = str_replace(str_replace("public", "", URL::to('/') . '/'), "", $request->path);
                }
                User::where('id', Auth::user()->id)->update($file);

                $user = PatientFamilyMember::where('userId', auth()->user()->id)->first();
                return fractal()->item($user)->transformWith(new PatientFamilyMemberTransformer(true))->toArray();
            } else {
                Staff::where('userId', Auth::user()->id)->update([
                    "phoneNumber" => $request->phoneNumber,
                    "updatedBy" => Auth::user()->id,
                ]);

                $file = array();
                if (!empty($request->path)) {
                    $file['profilePhoto'] = str_replace(str_replace("public", "", URL::to('/') . '/'), "", $request->path);
                }
                User::where('id', Auth::user()->id)->update($file);
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
            $patient = Patient::where('userId', $id)->first();
            if ($patient) {
                $user = User::where('id', $id)->whereHas('patient', function ($query) use ($id) {
                    $query->where('userId', $id);
                })->first();
            } else {
                $user = User::where('id', $id)->whereHas('staff', function ($query) use ($id) {
                    $query->where('userId', $id);
                })->first();
            }
            return fractal()->item($user)->transformWith(new UserTransformer(true))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function passwordChange(Request $request)
    {
        try {
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->newPassword)]);
            return response()->json(['message' => trans('messages.changePassword')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function loginFirst(Request $request)
    {
        try {
            User::where('id',auth()->user()->id)->update(['firstLogin' => 0]);
            return response()->json(['message' => trans('messages.updatedSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $post = $request->all();
            if(isset($post["email"]) && !empty($post["email"])){
                $email = $post["email"];
            }else{
                $email = "";
            }

            if(isset($post["phone"]) && !empty($post["phone"])){
                $phone = $post["phone"];
            }else{
                $phone = "";
            }

            if(empty($email) && empty($phone)){
                return response()->json(['message' => "Invalid Email"], 500);
            }else{
                $result = User::where('email',$email)->first();
                if($result){
                    $code = base64_encode($email)."##".$result->udid;
                    $forgotUrl = URL()."/generate/newPassword?code=".$code;
                    return response()->json(["forgotURl" =>$forgotUrl,"forgotCode" =>$code,'message' => "Url Generated Successfully."]);
                }else{
                    return response()->json(['message' => "Invalid Email."], 500);
                } 
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function newPassword(Request $request)
    {
        $get = $request->all();
        if(isset($get["code"]) && !empty($get["code"]))
        {
            $codeObj = explode("##",$get["code"]);
            if(isset($codeObj[0])){
                $email = base64_decode($codeObj[0]);
            }else{
                $email = "";
            }

            if(isset($codeObj[1])){
                $udid = base64_decode($codeObj[1]);
            }else{
                $udid = "";
            }

            if(isset($get["newPassword"]) && !empty($get["newPassword"]))
            {
                $newPassword = $get["newPassword"];
            }else{
                $newPassword = "";
            }


            $result = User::where('email',$email)
                        ->where('udid',$udid)->first();
            if($result){
                User::find($result->id)->update(['password' => Hash::make($newPassword)]);
                return response()->json(['message' => "Password Changed Successfully."]);
            }else{
                return response()->json(['message' => "Invalid code."], 500);
            }

        }else{
            return response()->json(['message' => "Invalid code."], 500);
        }
    }
}
