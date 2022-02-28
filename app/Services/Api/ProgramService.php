<?php

namespace App\Services\Api;

use Exception;
use App\Models\Program\Program;
use Illuminate\Support\Str;
use App\Transformers\Program\ProgramTransformer;
use Illuminate\Support\Facades\DB;

class ProgramService
{
    public function programList($request)
    {
        try{
            $getProgram = Program::with('type')->get();
            return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function createProgram($request)
    {
        try {
            $udid = Str::random(10);
            $typeId = $request->input('typeId');
            $description = $request->input('description');
            DB::select('CALL createProgram("' . $udid . '","' . $typeId . '","' . $description . '")');
            $newData = Program::latest('udid')->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($newData)->transformWith(new ProgramTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateProgram($request,$id)
    {
        try {
            $program = [
                'typeId' => $request->input('typeId'),
                'description' => $request->input('description'),
                'updatedBy' =>1,
            ];
            Program::where('udid', $id)->update($program);
            $newData = Program::where('udid', $id)->first();
            $message = ["message" => "updated Successfully"];
            $resp =  fractal()->item($newData)->transformWith(new ProgramTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteProgram($request,$id)
    {
        try {
            $program = Program::where('udid', $id)->first();
            $input=['deletedBy'=>1,'isActive'=>0,'isDelete'=>1];
            Program::where('udid', $id)->update($input);
            Program::where('udid', $id)->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}