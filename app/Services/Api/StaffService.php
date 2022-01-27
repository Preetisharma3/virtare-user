<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\UserTransformer;
use App\Transformers\Staff\StaffTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class StaffService
{
    public function addStaff($request)
    {
        try {
            $user = [
                'udid' => Str::random(10),
                'email' => $request->email,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // 'password'
                'emailVerify' => 1,
                'createdBy' => 1,
                'roleId'=>3,
            ];
            $data = User::create($user);
            $staff = [
                'udid' =>Str::random(10),
                'userId'=>$data->id,
                'email' => $data->email,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phoneNumber' => $request->phoneNumber,
                'genderId' => $request->genderId,
                'specializationId' => $request->specializationId,
                'designationId' => $request->designationId,
                'networkId' => $request->networkId,
                'providerId'=>$request->providerId,
                'roleId'=>3,
                'createdBy' => 1
            ];
            $newData = Staff::create($staff);
            $staffData= Staff::where('id',$newData->id)->first();
            $message = ["message"=>"created Successfully"];
          $resp =  fractal()->item($staffData)->transformWith(new StaffTransformer())->toArray();
          $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listStaff($request){
        $data = Staff::with('roles')->get();
        return fractal()->collection($data)->transformWith(new StaffTransformer())->toArray();
    }
}
