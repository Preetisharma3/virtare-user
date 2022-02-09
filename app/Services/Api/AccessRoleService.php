<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Transformers\AccessRoles\AccessRoleTransformer;
use App\Transformers\AccessRoles\AssignedRolesTransformer;

class AccessRoleService
{

    public function index()
    {
        try {
            $data = DB::select(
                'CALL accessRolesList()',
            );
            return fractal()->collection($data)->transformWith(new AccessRoleTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function assignedRoles($id)
    {
        try {
            if($id){
            $staffId = $id;
            }
            else{
                $staffId = auth()->user()->staff->id;
            }
            $data = DB::select(
                'CALL assignedRolesList('.$staffId.')',
            );
            return fractal()->collection($data)->transformWith(new AssignedRolesTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
