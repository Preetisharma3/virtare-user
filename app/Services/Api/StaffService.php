<?php

namespace App\Services\Api;

use Exception;
use App\Models\User\User;
use App\Models\Staff\Staff;
use App\Models\StaffAvailability\StaffAvailability;
use App\Models\StaffContact\StaffContact;
use App\Models\UserRole\UserRole;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Transformers\User\UserTransformer;
use App\Transformers\Staff\StaffTransformer;
use App\Transformers\Staff\StaffContactTransformer;
use App\Transformers\Staff\StaffAvailabilityTransformer;
use App\Transformers\Staff\StaffRoleTransformer;
use Illuminate\Support\Facades\DB;
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
        $data = Staff::with('roles','appointment')->get();
        return fractal()->collection($data)->transformWith(new StaffTransformer())->toArray();
    }

    public function updateStaff($request,$id)
    {
        $staffId=Staff::where('id',$id)->first();
        $uId=$staffId->userId;

        $user = [
            'email' => $request->input('email'),
            'updatedBy' => 1
       ];
       User::where('id',$uId)->update($user);

       $staff = [
        'email' => $user['email'],
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
        Staff::where('id',$id)->update($staff);
        $staffData= Staff::where('id',$id)->first();
        $message = ["message"=>"Updated Successfully"];
        $resp =  fractal()->item($staffData)->transformWith(new StaffTransformer())->toArray();
        $endData = array_merge($message, $resp);
        return $endData;
    }


    public function addStaffContact($request , $id)
    {
       try{
        $udid = Str::random(10);
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $phoneNumber = $request->phoneNumber;
        $staffId = $id;
        DB::select('CALL createStaffContact("'.$udid.'","'.$firstName.'","'.$lastName.'","'.$email.'","'.$phoneNumber.'","'.$staffId.'")');
        $staffContactData = StaffContact::where('udid', $udid)->first();
        $message = ["message"=>"created Successfully"];
        $resp =  fractal()->item($staffContactData)->transformWith(new StaffContactTransformer())->toArray();
        $endData = array_merge($message, $resp);
        return $endData;
    }catch (Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);  
       }
        
    }


    public function listStaffContact($request)
    {
        try{
            $staffContact = StaffContact::all();
            return  fractal()->collection($staffContact)->transformWith(new StaffContactTransformer())->toArray();
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500); 
        }
        
    }

    public function updateStaffContact($request, $id)
    {
        try{
            $staffContact = [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'phoneNumber' => $request->input('phoneNumber'),
            ];
            StaffContact::where('id',$id)->update($staffContact);
            $staffContactData = StaffContact::where('id', $id)->first();
            $message = ["message"=>"Updated Successfully"];
            $resp =  fractal()->item($staffContactData)->transformWith(new StaffContactTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteStaffContact($request, $id)
    {
        try{
            StaffContact::where('id',$id)->delete();

            return response()->json(['message' =>"Deleted Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }
    }


    public function addStaffAvailability($request, $id)
    {
        try{
            $udid = Str::random(10);
            $startTime = $request->startTime;
            $endTime = $request->endTime;
            $staffId = $id;
            DB::select('CALL createStaffAvailability("'.$udid.'","'.$startTime.'","'.$endTime.'","'.$staffId.'")');
            $staffAvailability = StaffAvailability::where('udid',$udid)->first();
            $message = ["message"=>"created Successfully"];
            $resp =  fractal()->item($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
       }
       
    }

    public function listStaffAvailability($request)
    {
        try{
            $staffAvailability = StaffAvailability::all();
            return fractal()->collection($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }

    }

    public function updateStaffAvailability($request, $id)
    {
        try{
            $staffAvailability = [
                'startTime' => $request->input('startTime'),
                'endTime' => $request->input('endTime'),
            ];
            StaffAvailability::where('id',$id)->update($staffAvailability);
            $staffAvailability = StaffAvailability::where('id',$id)->first();
            $message = ["message"=>"Updated Successfully"];
            $resp =  fractal()->item($staffAvailability)->transformWith(new StaffAvailabilityTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
       }
    }

    public function deleteStaffAvailability($request, $id)
    {
        try{
            StaffAvailability::where('id',$id)->delete();

            return response()->json(['message' =>"Deleted Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }
    }

    public function addStaffRole($request, $id)
    {
        try{
             $udid = Str::random(10);
             $userId = $id;
             $roleId = $request->roleId;
             DB::select('CALL createstaffRole("'.$udid.'","'.$userId.'","'.$roleId.'")');
             return response()->json(['message' => "created Successfully"]);
        }catch (Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);  
       }
    }

    public function listStaffRole($request)
    {
        try{
            $staffRole = UserRole::with('roles')->get();
            return fractal()->collection($staffRole)->transformWith(new StaffRoleTransformer())->toArray();
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        }
        
    }

    public function updateStaffRole($request,$id)
    {
        try{
            $staffRole = [
                'userId' => $request->input('userId'),
                'roleId' => $request->input('roleId'),
            ];
            UserRole::where('id',$id)->update($staffRole);
            return response()->json(['message' => "Updated Successfully"]);
       }catch (Exception $e){
       return response()->json(['message' => $e->getMessage()], 500);  
      }
    }

    public function deleteStaffRole($request,$id)
    {
        try{
            UserRole::where('id',$id)->delete();

            return response()->json(['message' =>"Deleted Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }
    }

}
