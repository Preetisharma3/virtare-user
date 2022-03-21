<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\AccessRoleService;

class AccessRoleController extends Controller
{
    public function index()
    {
        return (new AccessRoleService)->index();
    }

    public function assignedRoles($id = null)
    {
        return (new AccessRoleService)->assignedRoles($id);
    }

    public function assignedRoleAction($id = null)
    {
        return (new AccessRoleService)->assignedRoleAction($id);
    }
}
