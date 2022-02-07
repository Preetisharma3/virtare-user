<?php

namespace App\Services\Api;

use App\Models\Module\Module;
use App\Models\Role\Role;
use Exception;
use Illuminate\Support\Str;
use App\Transformers\Role\RoleTransformer;
use App\Transformers\Role\RoleListTransformer;
use App\Transformers\RolePermission\PermissionTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolePermissionService
{

    public function roleList($request)
    {
        try{
            $data = Role::all();
            return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        } 
    }

    public function updateRole($request, $id)
    {
        try{
            $role = [
                'roles' => $request->input('roles'),
                'roleDescription' => $request->input('roleDescription'),
                'roleType' => $request->input('roleType'),
                'masterLogin' => $request->input('masterLogin'),
                'isActive' => $request->input('isActive'),
            ];
            Role::where('id',$id)->update($role);
            $roleData = Role::where('id', $id)->first();
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
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            Role::find($id)->update($data);
            Role::find($id)->delete();

            return response()->json(['message' =>"Deleted Successfully"]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);   
        }
    }


    public function permissionsList($request)
    {
        try{
            $data = Module::with('screens')->get();
            return fractal()->collection($data)->transformWith(new PermissionTransformer())->toArray();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        }
        
    }
    
    
    public function createRole($request)
    {
        try{
            $udid = Str::random(10);
            $roles = $request->roles;
            $roleDescription = $request->roleDescription;
            $roleType = $request->roleType;
            $masterLogin = $request->masterLogin;
            DB::select('CALL createRole("'.$udid.'","'.$roles.'","'.$roleDescription.'","'.$roleType.'","'.$masterLogin.'")');
            $role = Role::where('udid', $udid)->first();
            $message = ["message"=>"created Successfully"];
            $resp =  fractal()->item($role)->transformWith(new RoleTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function createPermission($request)
    {
        try{
            $udid = Str::random(10);
            $providerId = $request->providerId;
            $providerLocationId = $request->providerLocationId;
            $roleId = $request->roleId;
            $actionId = $request->actionId;
            DB::select('CALL createPermission("'.$udid.'","'.$providerId.'","'.$providerLocationId.'","'.$roleId.'","'.$actionId.'")');
            
            return response()->json(['message' =>'Created Successfully']);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }


    public function createRoleModule($request)
    {
        try{
            $udid = Str::random(10);
            $providerId = $request->providerId;
            $roleId = $request->roleId;
            $moduleId = $request->moduleId;
            $moduleAccess = $request->moduleAccess;
            DB::select('CALL createRoleModule("'.$udid.'","'.$providerId.'","'.$roleId.'","'.$moduleId.'","'.$moduleAccess.'")');
            
            return response()->json(['message' =>'Created Successfully']);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function createRoleModuleScreen($request)
    {
        try {
              $udid = Str::random(10);
              $providerId = $request->providerId;
              $roleModuleId = $request->roleModuleId;
              $screenId = $request->screenId;
              $description = $request->description;
              $screenAccess = $request->screenAccess;
              DB::select('CALL createRoleModuleScreen("'.$udid.'","'.$providerId.'","'.$roleModuleId.'","'.$screenId.'","'.$description.'","'.$screenAccess.'")');
              return response()->json(['message' =>'Created Successfully']);
        }catch (Exception $e) {
          return response()->json(['message' => $e->getMessage()], 500);  
        }
    }

    public function createRolePermission($request)
    {
        try{
            $udid = Str::random(10);
            $providerId = $request->providerId;
            $permissionId = $request->permissionId;
            $roleModuleScreenId = $request->roleModuleScreenId;
            $actionId = $request->actionId;
            $actionAccess = $request->actionAccess;
            DB::select('CALL createRolePermission("'.$udid.'","'.$providerId.'","'.$permissionId.'","'.$roleModuleScreenId.'","'.$actionId.'","'.$actionAccess.'")');
            return response()->json(['message' =>'Created Successfully']);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

}
