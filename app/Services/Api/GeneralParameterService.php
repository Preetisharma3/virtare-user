<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralParameter\GeneralParameter;
use App\Models\GeneralParameter\GeneralParameterGroup;
use App\Transformers\GeneralParameter\GeneralParameterTransformer;
use App\Transformers\GeneralParameter\GeneralParameterGroupTransformer;

class GeneralParameterService
{
    public function generalParameterAdd($request,$id)
    {
        DB::beginTransaction();
        try {
            if(!$id){
                $group = ['name' => $request->input('generalParameterGroup'),'deviceTypeId'=>$request->input('deviceType'), 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString()];
                $groupData = GeneralParameterGroup::create($group);
                $parameter=$request->input('parameter');
                foreach($parameter as $value){
                    $input = [
                        'generalParameterGroupId' => $groupData->id, 'vitalFieldId' => $value['type'],
                        'highLimit' => $value['highLimit'], 'lowLimit' => $value['lowLimit'], 'createdBy' => 1, 'udid' => Str::uuid()->toString()
                    ];
                    GeneralParameter::create($input);
                }
                $data = GeneralParameterGroup::where('id', $groupData->id)->with('generalParameter')->first();
                $userdata = fractal()->item($data)->transformWith(new GeneralParameterGroupTransformer())->toArray();
                $message = ['message' => 'created successfully'];
            }else{
                $input = [
                    'vitalFieldId' =>$request->input('type'),'highLimit' => $request->input('highLimit'), 'lowLimit' => $request->input('lowLimit'), 'updatedBy' => Auth::id()
                ];
                GeneralParameter::where('udid', $id)->update($input);
                $data = GeneralParameter::where('udid', $id)->with('generalParameterGroup')->first();
                $userdata = fractal()->item($data)->transformWith(new GeneralParameterTransformer())->toArray();
                $message = ['message' => 'updated successfully'];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function generalParameterGroupList($request, $id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = GeneralParameterGroup::with('generalParameter')->orderBy('createdAt','DESC')->get();
                return fractal()->collection($data)->transformWith(new GeneralParameterGroupTransformer())->toArray();
            } else {
                $data = GeneralParameterGroup::where('udid', $id)->with('generalParameter')->first();
                return fractal()->item($data)->transformWith(new GeneralParameterGroupTransformer())->toArray();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function generalParameterList($request, $id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                $data = GeneralParameter::with('generalParameterGroup')->orderBy('createdAt','DESC')->get();
                return fractal()->collection($data)->transformWith(new GeneralParameterTransformer())->toArray();
            } else {
                $data = GeneralParameter::where('udid', $id)->with('generalParameterGroup')->first();
                return fractal()->item($data)->transformWith(new GeneralParameterTransformer())->toArray();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    public function generalParameterGroupDelete($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0
            ];
            GeneralParameterGroup::where('udid', $id)->update($input);
            GeneralParameter::where('generalParameterGroupId', $id)->update($input);
            GeneralParameterGroup::where('udid', $id)->delete();
            GeneralParameter::where('generalParameterGroupId', $id)->delete();
            DB::commit();
            return response()->json(['message' => 'deleted successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function generalParameterDelete($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0
            ];
            GeneralParameter::where('udid', $id)->update($input);
            GeneralParameter::where('udid', $id)->delete();
            DB::commit();
            return response()->json(['message' => 'deleted successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
