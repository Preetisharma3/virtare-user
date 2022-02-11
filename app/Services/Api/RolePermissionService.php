<?php

namespace App\Services\Api;

use App\Models\Module\Module;
use App\Models\Permission\Permission;
use App\Models\Role\AccessRole;
use App\Models\Role\Role;
use App\Models\RolePermission\RolePermission;
use Exception;
use Illuminate\Support\Str;
use App\Transformers\Role\RoleTransformer;
use App\Transformers\Role\RoleListTransformer;
use App\Transformers\RolePermission\PermissionTransformer;
use App\Transformers\RolePermission\RolePermissionTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolePermissionService
{

    public function roleList($request)
    {
        try{
            $data = AccessRole::all();
            return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        } 
    }

    public function createRole($request)
    {
        try{
            $role = [
                'udid' => Str::random(10),
                'roles' => $request->input('name'),
                'roleDescription' => $request->input('description'),
                'roleTypeId' => $request->input('roleTypeId'),
            ];
            AccessRole::create($role);
            $role = AccessRole::where('udid', $role['udid'])->first();
            $message = ["message"=>"created Successfully"];
            $resp =  fractal()->item($role)->transformWith(new RoleListTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;

        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           } 
    }

    public function listingRole($request,$id)
    {
        $data = AccessRole::where('id', $id)->get();
        return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
    }

    public function updateRole($request, $id)
    {
        try{
            $role = [
                'roles' => $request->input('roles'),
                'roleDescription' => $request->input('roleDescription'),
                'isActive' => $request->input('isActive'),
            ];
        AccessRole::where('id',$id)->update($role);
            $roleData = AccessRole::where('id', $id)->first();
            $message = ["message"=>"Updated Successfully"];
            $resp =  fractal()->item($roleData)->transformWith(new RoleListTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteRole($request, $id)
    {
        try{
            AccessRole::where('id',$id)->delete();

            return response()->json(['message' =>"Deleted Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }
    }


    public function createRolePermission($request,$id)
    {
        try{
            $action = $request->actions;
            foreach($action as $actionId ){
                $rolePermission = [
                    'udid' => Str::random(10),
                    'accessRoleId' => $id,
                    'actionId' => $actionId,
                ];
                RolePermission::create($rolePermission);
            }
            
            return response()->json(['message' =>"Created Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }


    public function rolePermissionList($request)
    {
        try{
            $id = $request->id;
            $data = RolePermission::where('accessRoleId',$id)->with('role','action')->get();
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
}
