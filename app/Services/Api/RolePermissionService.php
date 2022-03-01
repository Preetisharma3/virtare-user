<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Module\Module;
use App\Models\Role\AccessRole;
use Illuminate\Support\Facades\DB;
use App\Models\RolePermission\RolePermission;
use App\Transformers\Role\RoleListTransformer;
use App\Transformers\RolePermission\PermissionTransformer;
use App\Transformers\RolePermission\RolePermissionTransformer;

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
                'roleTypeId' => '162',
            ];
            $data = AccessRole::create($role);
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           } 
    }

    public function updateRole($request, $id)
    {
        try{
            $role = [
                'roles' => $request->input('name'),
                'roleDescription'=> $request->input('description'),
                'roleTypeId' => '162',
            ];
            AccessRole::where('udid', $id)->update($role);
            return response()->json(['message' => trans('messages.updatedSuccesfully')]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteRole($request,$id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $input=['deletedBy'=>1,'isActive'=>0,'isDelete'=>1];
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
            $action = $request->actions;
            foreach($action as $actionId ){
                $udid = Str::uuid()->toString();
                $accessRoleId = $id;
                $actionId = $actionId;
                DB::select('CALL createRolePermission("' . $udid . '","' . $accessRoleId . '","' . $actionId . '")'); 
            }
            
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
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
