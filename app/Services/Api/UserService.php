<?php

namespace App\Services\Api;

use App\Models\Document\Document;
use App\Models\Patient\Patient;
use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use App\Transformers\User\UserPatientTransformer;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\UserTransformer;

class UserService
{
    public function userProfile($request)
    {
        try {
            if (auth()->user()->roleId == 4) {
                $data = User::where('id', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new UserPatientTransformer())->toArray();
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
                    "nickName" => $request->nick_name,
                    "phoneNumber" => $request->contact_no,
                    "updatedBy" => Auth::user()->id,
                ]);
                Document::where([['referanceId', Auth::user()->id],['entityType','patient']])->update([
                    "filePath"=>$request->path,
                ]);
                $user = User::where('udid', Auth::user()->udid)->first();
                return fractal()->item($user)->transformWith(new UserPatientTransformer(true))->toArray();
            } else {
                Staff::where('userId', Auth::user()->id)->update([
                    "phoneNumber" => $request->phoneNumber,
                    "updatedBy" => Auth::user()->id,
                ]);
                $user = User::where('udid', Auth::user()->udid)->first();
                return fractal()->item($user)->transformWith(new UserTransformer(true))->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
