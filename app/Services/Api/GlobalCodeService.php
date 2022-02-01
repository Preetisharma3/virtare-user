<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\GlobalCode\GlobalCode;
use App\Models\GlobalCode\GlobalCodeCategory;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientFlag;
use App\Models\Patient\PatientInsurance;
use App\Models\Patient\PatientProgram;
use App\Transformers\GlobalCode\GlobalCodeTransformer;
use App\Transformers\GlobalCode\GlobalCodeCategoryTransformer;

class GlobalCodeService
{
     public function globalCodeCategoryList($request, $id)
     {
          try {
               if (!$id) {
                    $global = GlobalCodeCategory::with('globalCode')->get();
                    return fractal()->collection($global)->transformWith(new GlobalCodeCategoryTransformer())->toArray();
               } else {
                    $global = GlobalCodeCategory::where('id', $id)->with('globalCode')->first();
                    return fractal()->item($global)->transformWith(new GlobalCodeCategoryTransformer())->toArray();
               }
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }


     public function globalCodeCreate($request)
     {
          try {
               $merge = $request->merge([
                    'globalCodeCategoryId' => $request->globalCodeCategory, 'createdBy' => 1,
                    'udid' => Str::uuid()->toString(), 'isActive' => $request->status
               ]);
               $global = GlobalCode::create($merge->only([
                    'globalCodeCategoryId', 'name', 'description', 'createdBy', 'isActive', 'udid'
               ]));
               $data = GlobalCode::whereHas('globalCodeCategory', function ($q) use ($global) {
                    $q->where('id', $global->globalCodeCategoryId);
               })->where('id', $global->id)->with('globalCodeCategory')->first();
               $category = GlobalCodeCategory::where('id', $data->globalCodeCategoryId);
               $userdata = fractal()->item($data)->transformWith(new GlobalCodeTransformer())->toArray();
               $message = ['message' => 'created successfully'];
               $endData = array_merge($message, $userdata);
               return $endData;
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }



     public function globalCodeUpdate($request, $id)
     {
          try {
               $merge = $request->merge(['globalCodeCategoryId' => $request->globalcodecategory, 'updatedBy' => 1, 'isActive' => $request->status]);
               $global = GlobalCode::find($id)->update($merge->only([
                    'globalCodeCategoryId', 'name', 'description', 'updatedBy', 'isActive'
               ]));
               $input = GlobalCode::find($id)->first();
               $data = GlobalCode::whereHas('globalCodeCategory', function ($q) use ($input, $id) {
                    $q->where('id', $input['globalCodeCategoryId']);
               })->where('id', $id)->with('globalCodeCategory')->first();
               $userdata = fractal()->item($data)->transformWith(new GlobalCodeTransformer())->toArray();
               $message = ['message' => 'updated successfully'];
               $endData = array_merge($message, $userdata);
               return $endData;
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }


     public function globalCodeDelete($request, $id)
     {
          try {
               $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
               GlobalCode::find($id)->update($data);
               GlobalCode::find($id)->delete();
               return response()->json(['message' => 'delete successfully']);
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }
}
