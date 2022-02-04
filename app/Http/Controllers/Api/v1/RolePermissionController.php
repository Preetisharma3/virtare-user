<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\RolePermissionService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    
    public function createRole(Request $request)
    {
        return (new RolePermissionService)->createRole($request);
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
