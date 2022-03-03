<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Module\Module;
use App\Models\Role\AccessRole;
use Illuminate\Support\Facades\DB;
use App\Models\RolePermission\RolePermission;
use App\Transformers\Role\RoleListTransformer;
use App\Transformers\RolePermission\RolePerTransformer;
use App\Transformers\RolePermission\PermissionTransformer;
use App\Transformers\RolePermission\RolePermissionTransformer;
use Illuminate\Support\Facades\Auth;

class RolePermissionService
{

    public function roleList($request,$id)
    {
        try{
            if(!$id){
                $data = AccessRole::all();
                return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
            }else{
                $data = AccessRole::where('udid',$id)->get();
                return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        } 
    }

    public function createRole($request)
    {
        try{
            $role = [
                'udid' => Str::uuid()->toString(),
                'roles' => $request->input('name'),
                'roleDescription'=> $request->input('description'),
                'roleTypeId' => '147',
            ];
            $data = AccessRole::create($role);
            $roleData = AccessRole::where('id', $data->id)->first();
            $message = ["message" => trans('messages.createdSuccesfully')];
            $resp =  fractal()->item($roleData)->transformWith(new RoleListTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           } 
    }

    public function updateRole($request, $id)
    {
        try{
        $role = array();
        if(!empty($request->input('name'))){
            $role['roles'] =  $request->input('name');
        }
        if(!empty($request->input('description'))){
            $role['roleDescription'] =  $request->input('description');
        }
        if(!empty($request->input('isActive'))){
            $role['isActive'] =  $request->input('isActive');
        }
        $role['updatedBy'] =  Auth::id();
        
        if(!empty($role)){
            AccessRole::where('udid', $id)->update($role);
        }
            return response()->json(['message' => trans('messages.updatedSuccesfully')]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteRole($request,$id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $input=['deletedBy'=>Auth::id(),'isActive'=>0,'isDelete'=>1];
            AccessRole::where('udid', $id)->update($input);
            AccessRole::where('udid', $id)->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function createRolePermission($request,$id)
    {
        try{
            $role = AccessRole::where('udid',$id)->first();
            $action = $request->actions;
            foreach($action as $actionId ){
                $udid = Str::uuid()->toString();
                $accessRoleId = $role->id;
                $actionId = $actionId;
                DB::select('CALL createRolePermission("' . $udid . '","' . $accessRoleId . '","' . $actionId . '")'); 
            }
            
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }


    public function rolePermissionList($request,$id)
    {
        try{
            $role = AccessRole::where('udid',$id)->first();
            $data = RolePermission::where('accessRoleId',$role->id)->with('role','action')->get();
            $array  = ['role' => fractal()->collection($data)->transformWith(new RolePermissionTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()];
            return $array;
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        }
    }

    public function permissionsList($request)
    {
        try{
            $data = Module::with('screens')->get();
            $array = ['modules'=>fractal()->collection($data)->transformWith(new PermissionTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()];
            return  $array;
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        }
        
    }

    public function rolePermissionEdit($id)
    {
     try{
        $role = AccessRole::where('udid',$id)->first();
        $data = DB::select('CALL rolePermissionListing(' . $role->id . ')');
        return fractal()->collection($data)->transformWith(new RolePerTransformer())->toArray();
    }catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);    
    }
 }
}
