<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralParameter\GeneralParameter;
use App\Models\GeneralParameter\GeneralParameterGroup;
use App\Transformers\GeneralParameter\GeneralParameterTransformer;
use App\Transformers\GeneralParameter\GeneralParameterGroupTransformer;

class GeneralParameterService
{
    public function generalParameterAdd($request)
    {
        DB::beginTransaction();
        try {
            $group = ['name' => $request->input('generalParameterGroup'), 'createdBy' => 1, 'udid' => Str::uuid()->toString()];
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
            $message = response()->json(['message' => trans('messages.created_succesfully')],200);
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
                $data = GeneralParameterGroup::with('generalParameter')->get();
                return fractal()->collection($data)->transformWith(new GeneralParameterGroupTransformer())->toArray();
            } else {
                $data = GeneralParameterGroup::where('id', $id)->with('generalParameter')->first();
                return fractal()->item($data)->transformWith(new GeneralParameterGroupTransformer())->toArray();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }


    public function generalParameterUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'vitalFieldId' => json_encode($request->input('type')),
                'highLimit' => $request->input('highLimit'), 'lowLimit' => $request->input('lowLimit'), 'updatedBy' => 1
            ];
            GeneralParameter::where('id', $id)->update($input);
            $data = GeneralParameter::where('id', $id)->with('generalParameterGroup')->first();
            $userdata = fractal()->item($data)->transformWith(new GeneralParameterTransformer())->toArray();
            $message = response()->json(['message' => trans('messages.updated_succesfully')],200);
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
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
                'updatedBy' => 1, 'isDelete' => 1, 'isActive' => 0
            ];
            GeneralParameterGroup::where('id', $id)->update($input);
            GeneralParameter::where('generalParameterGroupId', $id)->update($input);
            GeneralParameterGroup::where('id', $id)->delete();
            GeneralParameter::where('generalParameterGroupId', $id)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deleted_succesfully')],200);
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
                'updatedBy' => 1, 'isDelete' => 1, 'isActive' => 0
            ];
            GeneralParameter::where('id', $id)->update($input);
            GeneralParameter::where('id', $id)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deleted_succesfully')],200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
