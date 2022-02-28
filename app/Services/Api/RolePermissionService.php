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

    public function roleList($request)
    {
        try{
            if($request->id){
                $data = AccessRole::where('id',$request->id)->get();
                return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
            }else{
                $data = AccessRole::all();
                return fractal()->collection($data)->transformWith(new RoleListTransformer())->toArray();
            }
            
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        } 
    }

    public function createRole($request)
    {
        try{
            // $udid = Str::uuid()->toString();
            // $roles = $request->input('name');
            // $roleDescription = $request->input('description');
            // $roleTypeId = $request->input('roleTypeId');
            // DB::select('CALL createRole("' . $udid . '","' . $roles . '","' . $roleDescription . '","'.$roleTypeId.'")'); 
            
            // $role = AccessRole::where('udid', $udid)->first();
            // $message = ['message' => trans('messages.createdSuccesfully')];
            // $resp =  fractal()->item($role)->transformWith(new RoleListTransformer())->toArray();
            // $endData = array_merge($message, $resp);
            // return $endData;
            $role = [
                'udid' => Str::uuid()->toString(),
                'roles' => $request->input('name'),
                'roleDescription'=> $request->input('description'),
                'roleTypeId' => '147',
            ];
            $data = AccessRole::create($role);
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
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
            $roles = $request->input('name');
            $roleDescription = $request->input('description');
            $roleTypeId = $request->input('roleTypeId');
            $isActive = $request->input('isActive');
            $updatedBy = 2;
            DB::select('CALL updateRole("'.$id.'","' . $roles . '","' . $roleDescription . '","' . $roleTypeId . '","'.$isActive.'","'.$updatedBy.'")');
            
            $roleData = AccessRole::where('id', $id)->first();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $resp =  fractal()->item($roleData)->transformWith(new RoleListTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteRole($request,$id)
    {
        try {
            $id = $request->id;
            $isDelete= 1;
            $deletedBy =2;
            $deletedAt = date('Y-m-d H:i:s');
            DB::select('CALL deleteRole("'.$id.'","'.$isDelete.'","'.$deletedBy.'","'.$deletedAt.'")');
            return response()->json(['message' => trans('messages.deletedSuccesfully')], 200);
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
