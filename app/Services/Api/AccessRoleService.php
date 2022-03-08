<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Facades\DB;
use App\Transformers\AccessRoles\AccessRoleTransformer;
use App\Transformers\AccessRoles\AssignedRoleActionTransformer;
use App\Transformers\AccessRoles\AssignedRolesTransformer;
use App\Transformers\AccessRoles\AssignedRoleWidgetTransformer;

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
            if ($id) {
                $staff = Helper::entity('staff', $id);
            } else {
                
                if(isset(auth()->user()->staff->id)){
                    $staff = auth()->user()->staff->id;
                }else{
                    $staff = "";
                }
            }

            if(!empty($staff)){
                $data = DB::select(
                    'CALL assignedRolesList(' . $staff . ')',
                );
            }else{
                $data = [];
            }
            return fractal()->collection($data)->transformWith(new AssignedRolesTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    public function assignedRoleAction($id)
    {
        try {
            if ($id) {
                $staff = Helper::entity('staff', $id);
            } else {
                
                if(isset(auth()->user()->staff->id)){
                    $staff = auth()->user()->staff->id;
                }else{
                    $staff = "";
                }
            }

            if(!empty($staff)){
                $data = DB::select(
                    'CALL assignedRolesActionList('.$staff.')',
                );
            }else{
                $data = [];
            }

            $action= [];
            $widget= [];
            foreach( $data as $new){
                $actionidx = $new->actionId;
                array_push($action,$actionidx);
                $widgetidx = $new->widgetId;
                array_push($widget,$widgetidx);
            }
            $daat = [
                'actionId'=>$action,
                'widgetId'=>$widget,
            ];
            return $daat;

            //  $finalAction['action']=fractal()->collection($data)->transformWith(new AssignedRoleActionTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
            //  $finalWidget['widget']=fractal()->collection($data)->transformWith(new AssignedRoleWidgetTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
            //    return array_merge($finalAction,$finalWidget);
            } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
