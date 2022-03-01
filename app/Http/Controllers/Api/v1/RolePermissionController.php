<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Services\Api\RolePermissionService;

class RolePermissionController extends Controller
{

    public function roleList(Request $request,$id=null)
    {
        return (new RolePermissionService)->roleList($request,$id); 
    }
    
    public function createRole(RoleRequest $request)
    {
        return (new RolePermissionService)->createRole($request);
    }

    public function updateRole(Request $request, $id)
    {
        return (new RolePermissionService)->updateRole($request, $id); 
    }

    public function deleteRole(Request $request, $id)
    {
        return (new RolePermissionService)->deleteRole($request, $id); 
    }

    public function rolePermissionList(Request $request,$id)
    {
        return (new RolePermissionService)->rolePermissionList($request,$id);
    }

    public function createRolePermission(Request $request,$id)
    {
        return (new RolePermissionService)->createRolePermission($request,$id);
    }

    public function permissionsList(Request $request)
    {
        return (new RolePermissionService)->permissionsList($request);
    }

}
