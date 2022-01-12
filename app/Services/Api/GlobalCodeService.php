<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\GlobalCode\GlobalCode;
use App\Models\GlobalCode\GlobalCodeCategory;
use App\Transformers\GlobalCode\GlobalCodeTransformer;
use App\Transformers\GlobalCode\GlobalCodeCategoryTransformer;

class GlobalCodeService
{
     public function globalCodeCategoryList($request)
     {
          try {
               $global = GlobalCodeCategory::with('globalCode')->get();
               return fractal()->collection($global)->transformWith(new GlobalCodeCategoryTransformer())->toArray();
          } catch (Exception $e) {
               return response()->json(['message' => $e->getMessage()],  500);
          }
     }


     public function globalCodeCreate($request)
     {
          try {
               $merge = $request->merge(['globalCodeCategoryId' => $request->globalCodeCategory, 'createdBy' => 1]);
               $global = GlobalCode::create($merge->only([
                    'globalCodeCategoryId', 'name', 'description', 'createdBy', 'isActive'
               ]));
               $data = GlobalCode::whereHas('globalCodeCategory',function($q) use($global){
                    $q->where('id',$global->globalCodeCategoryId);
               })->where('id', $global->id)->with('globalCodeCategory')->first();
               $category=GlobalCodeCategory::where('id',$data->globalCodeCategoryId);
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
               $merge = $request->merge(['globalCodeCategoryId' => $request->globalcodecategory, 'updatedBy' => 1]);
               $global = GlobalCode::find($id)->update($merge->only([
                    'globalCodeCategoryId', 'name', 'description', 'updatedBy', 'isActive'
               ]));
               $input=GlobalCode::find($id)->first();
               $data = GlobalCode::whereHas('globalCodeCategory',function($q) use($input,$id){
                    $q->where('id',$input['globalCodeCategoryId']);
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
