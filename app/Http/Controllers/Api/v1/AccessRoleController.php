<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\AccessRoleService;

class AccessRoleController extends Controller
{
    public function index(){
        return (new AccessRoleService)->index();
    }

    public function assignedRoles($id=null){
        return (new AccessRoleService)->assignedRoles($id);
    }
}
