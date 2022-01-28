<?php

namespace App\Services\Api;

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
            if(auth()->user()->roleId == 3){
                $data = User::where('id', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new UserTransformer())->toArray();
            }elseif(auth()->user()->roleId == 4){
                $data = User::where('id', auth()->user()->id)->first();
                return fractal()->item($data)->transformWith(new UserPatientTransformer())->toArray();
            }else{
                return response()->json(['message' =>'Unauthorised']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function profile($data)
    {
        try {
            Staff::where('userId',Auth::user()->id)->update([
                "phoneNumber" => $data->phoneNumber,
                "updatedBy" => Auth::user()->id,
            ]);
            $user = User::where('udid', Auth::user()->udid)->first();
            return fractal()->item($user)->transformWith(new UserTransformer(true))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
