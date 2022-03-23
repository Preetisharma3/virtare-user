<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Program\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Program\ProgramTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramService
{
    public function programList($request)
    {
        
        try{
            
                if($request->all()){
                    $getProgram = Program::with('type')->get();
            DB::select('CALL getPrograms("' . $typeId . '","' . $description . '","' . $name . '","' . $isActive . '","' . $updatedBy . '")');

                    return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->toArray();
                }else{
                    $getProgram = Program::with('type')->paginate(env('PER_PAGE',20));
                    return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->paginateWith(new IlluminatePaginatorAdapter($getProgram))->toArray();    
                
                 }
            //      else{
            //     $program = Program::where('udid',$id)->get();
            //     return fractal()->collection($program)->transformWith(new ProgramTransformer())->toArray();
            // }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function createProgram($request)
    {
    try {
            $input = $request->only(['typeId', 'description', 'name']);
            $otherData = [
                'udid' => Str::uuid()->toString(),
                'createdBy' => 1
            ];
            $data = JSON_ENCODE(array_merge(
                $input,
                $otherData
            ));
            $id =  DB::select(
                "CALL createAddPrograms('" . $data . "')"
            );
            $message = ['message' => trans('messages.createdSuccesfully')];
            $endData = array_merge($message);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }
    
        
    


    public function updateProgram($request,$id)
    {
         try {
            $typeId = $request->typeId;
            $description = $request->description;
            $name = $request->name;
            $isActive = $request->isActive;
            $updatedBy = Auth::id();
            DB::select('CALL updatePrograms("' . $id . '","' . $typeId . '","' . $description . '","' . $name . '","' . $isActive . '","' . $updatedBy . '")');
        
            $message  = ['message' => trans('messages.updatedSuccesfully')];
            $newData = Program::where('udid', $id)->first();
            $data =  fractal()->item($newData)->transformWith(new ProgramTransformer())->toArray();
            $response = array_merge($message, $data);
            return $response;
         } catch (Exception $e) {
             return response()->json(['message' => $e->getMessage()], 500);
         }

    }
    



    public function deleteProgram($request,$id)
    {
      //  try {
            //  $id = $request->id;
            //  $program = Program::where('udid', $id)->first();
            $input = [
                'deletedBy'=>Auth::id(),'isDelete'=>1

            ];
            //dd($id);
             DB::select('CALL deletePrograms("' . $id . '")');
            //Program::where('udid', $id)->update($input);
            $test = Program::where('udid', $id)->delete();
 //dd($test);
            return response()->json(['message' => trans('messages.deletedSuccesfully')], 200);

        }
        // catch (Exception $e) {
         //   return response()->json(['message' => $e->getMessage()], 500);
      //  }



    }
