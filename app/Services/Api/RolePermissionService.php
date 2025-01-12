<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Module\Module;
use App\Models\Role\AccessRole;
use Illuminate\Support\Facades\DB;
use App\Models\RolePermission\RolePermission;
use App\Transformers\Role\RoleListTransformer;
use App\Transformers\RolePermission\RolePerTransformer;
use App\Transformers\RolePermission\PermissionTransformer;
use App\Transformers\RolePermission\RolePermissionTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class RolePermissionService
{

    public function roleList($request, $id)
    {
        try {
            if (!$id) {
                if ($request->active == 1) {
                    $data = AccessRole::paginate(env('PER_PAGE', 20));
                } else {
                    $data = AccessRole::where('isActive', 1)->paginate(env('PER_PAGE', 20));
                }
                return fractal()->collection($data)->transformWith(new RoleListTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            } else {
                $data = AccessRole::where('udid', $id)->first();
                return fractal()->item($data)->transformWith(new RoleListTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createRole($request)
    {
        try {
            $role = [
                'udid' => Str::uuid()->toString(),
                'roles' => $request->input('name'),
                'roleDescription' => $request->input('description'),
                'roleTypeId' => '147',
            ];
            $data = AccessRole::create($role);
            $roleData = AccessRole::where('id', $data->id)->first();
            $message = ["message" => trans('messages.createdSuccesfully')];
            $resp =  fractal()->item($roleData)->transformWith(new RoleListTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateRole($request, $id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $roleId = $role->id;
            if (($roleId == 1)) {
                return response()->json(['message' => 'unauthorized']);
            } else {
                $role = array();
                if (!empty($request->input('name'))) {
                    $role['roles'] =  $request->input('name');
                }
                if (!empty($request->input('description'))) {
                    $role['roleDescription'] =  $request->input('description');
                }
                if (empty($request->input('status'))) {
                    $role['isActive'] =  0;
                } else {
                    $role['isActive'] = 1;
                }
                $role['updatedBy'] =  Auth::id();

                if (!empty($role)) {

                    AccessRole::where('id', $roleId)->update($role);
                }
            }

            return response()->json(['message' => trans('messages.updatedSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($request, $id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $roleId = $role->id;
            if (($roleId == 1)) {
                return response()->json(['message' => 'unauthorized']);
            } else {
                $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
                AccessRole::where('id', $roleId)->update($input);
                AccessRole::where('id', $roleId)->delete();
                return response()->json(['message' => "Deleted Successfully"]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function createRolePermission($request, $id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1, 'deletedAt' => Carbon::now()];
            RolePermission::where('accessRoleId', $role->id)->update($input);

            $action = $request->actions;
            foreach ($action as $actionId) {
                $udid = Str::uuid()->toString();
                $accessRoleId = $role->id;
                $actionId = $actionId;
                DB::select('CALL createRolePermission("' . $udid . '","' . $accessRoleId . '","' . $actionId . '")');
            }

            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function rolePermissionList($request, $id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $data = RolePermission::where('accessRoleId', $role->id)->with('role', 'action')->get();
            $array  = ['role' => fractal()->collection($data)->transformWith(new RolePermissionTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()];
            return $array;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function permissionsList($request)
    {
        try {
            $data = Module::with('screens')->get();
            $array = ['modules' => fractal()->collection($data)->transformWith(new PermissionTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray()];
            return  $array;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rolePermissionEdit($id)
    {
        try {
            $role = AccessRole::where('udid', $id)->first();
            $data = DB::select('CALL rolePermissionListing(' . $role->id . ')');
            return fractal()->collection($data)->transformWith(new RolePerTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
