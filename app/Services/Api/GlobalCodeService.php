<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\GlobalCode\GlobalCode;
use App\Models\GlobalCode\GlobalCodeCategory;
use App\Transformers\GlobalCode\GlobalCodeTransformer;
use App\Transformers\GlobalCode\GlobalCodeCategoryTransformer;
use App\Transformers\GlobalCode\GlobalStartEndDateTransformer;

class GlobalCodeService
{
     public function globalCodeCategoryList($request, $id)
     {
          try {
               if (!$id) {
                    $global = GlobalCodeCategory::with('globalCode')->orderBy('name','ASC')->get();
                    return fractal()->collection($global)->transformWith(new GlobalCodeCategoryTransformer())->toArray();
               } else {
                    $global = GlobalCodeCategory::where('id', $id)->with('globalCode')->first();
                    return fractal()->item($global)->transformWith(new GlobalCodeCategoryTransformer())->toArray();
               }
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }

     public function globalCodeList($request, $id)
     {
          try {
               if (!$id) {
                    if($request->active){
                         $global = GlobalCode::orderBy('name','ASC')->get();
                    }else{
                         $global = GlobalCode::where('isActive',1)->orderBy('name','ASC')->get();
                    }
                    return fractal()->collection($global)->transformWith(new GlobalCodeTransformer())->toArray();
               } else {
                    $global = GlobalCode::where('id', $id)->first();
                    return fractal()->item($global)->transformWith(new GlobalCodeTransformer())->toArray();
               }
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }

     public function globalCodeCreate($request)
     {
          try {
              $input=[
                    'globalCodeCategoryId' => $request->globalCodeCategory, 'createdBy' => 1,
                    'udid' => Str::uuid()->toString(), 'isActive' => $request->status,'name'=>$request->input('name'),
                    'description'=>$request->input('description')
               ];
               $global = GlobalCode::create($input);
               $data = GlobalCode::whereHas('globalCodeCategory', function ($q) use ($global) {
                    $q->where('id', $global->globalCodeCategoryId);
               })->where('id', $global->id)->with('globalCodeCategory')->first();
               $category = GlobalCodeCategory::where('id', $data->globalCodeCategoryId);
               $userdata = fractal()->item($data)->transformWith(new GlobalCodeTransformer())->toArray();
               $message = ['message' => trans('messages.createdSuccesfully')];
               $endData = array_merge($message, $userdata);
               return $endData;
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }

     public function globalCodeUpdate($request, $id)
     {
          try {
               $globalCode = array();
               if (!empty($request->globalcodecategory)) {
                    $globalCode['globalCodeCategoryId'] = $request->globalcodecategory;
               }
               if (!empty($request->name)) {
                    $globalCode['name'] = $request->name;
               }
               if (!empty($request->description)) {
                    $globalCode['description'] = $request->description;
               }
               if (isset($request->status)) {
                    $globalCode['isActive'] = $request->status;
               }
               $globalCode['updatedBy'] = 1;
               $global = GlobalCode::find($id)->update($globalCode);
               $data = GlobalCode::where('id', $id)->with('globalCodeCategory')->first();
               $userdata = fractal()->item($data)->transformWith(new GlobalCodeTransformer())->toArray();
               $message = ['message' => trans('messages.updatedSuccesfully')];
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
               return response()->json(['message' => trans('messages.deletedSuccesfully')]);
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }

     public function getGlobalStartEndDate($request, $globalCodeId)
     {    
          if($globalCodeId)
          {
               $data = DB::select('CALL getGlobalStartEndDate(' . $globalCodeId . ')');
               if($data[0]){
                    $data = $data[0];
               }
               return fractal()->item($data)->transformWith(new GlobalStartEndDateTransformer())->toArray();
          }
          else
          {
               return response()->json(['message' => "globalCodeId required"],  500);
          }
     }
}
