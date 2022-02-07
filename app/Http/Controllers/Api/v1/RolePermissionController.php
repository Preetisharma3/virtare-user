<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\RolePermissionService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{

    public function roleList(Request $request)
    {
        return (new RolePermissionService)->roleList($request); 
    }
    
    public function createRole(Request $request)
    {
        return (new RolePermissionService)->createRole($request);
    }

    public function editRole(Request $request,$id)
    {
        return (new RolePermissionService)->editRole($request, $id);
    }

    public function updateRole(Request $request, $id)
    {
        return (new RolePermissionService)->updateRole($request, $id); 
    }

    public function deleteRole(Request $request, $id)
    {
        return (new RolePermissionService)->deleteRole($request, $id); 
    }

    public function createPermission(Request $request)
    {
        return (new RolePermissionService)->createPermission($request); 
    }

    public function createRoleModule(Request $request)
    {
        return (new RolePermissionService)->createRoleModule($request);  
    }

    public function createRoleModuleScreen(Request $request)
    {
        return (new RolePermissionService)->createRoleModuleScreen($request); 
    }

    public function createRolePermission(Request $request)
    {
        return (new RolePermissionService)->createRolePermission($request);
    }

    public function permissionsList(Request $request)
    {
        return (new RolePermissionService)->permissionsList($request);
    }

}
