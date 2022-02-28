<?php

namespace App\Services\Api;

use Exception;
use App\Models\Program\Program;
use Illuminate\Support\Str;
use App\Transformers\Program\ProgramTransformer;
use Illuminate\Support\Facades\DB;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProgramService
{
    public function programList($request)
    {
        try{
            $getProgram = Program::with('type')->paginate(env('PER_PAGE',20));
            return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->paginateWith(new IlluminatePaginatorAdapter($getProgram))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function createProgram($request)
    {
        try {
            $program = [
                'udid' => Str::random(10),
                'typeId' => $request->input('typeId'),
                'description' => $request->input('description'),
                'name' => $request->input('name'),
                'isActive' => $request->input('isActive'),
            ];
            $newData = Program::create($program);
            $staffData = Program::where('id', $newData->id)->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($staffData)->transformWith(new ProgramTransformer())->toArray();
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
                'name' => $request->input('name'),
                'isActive' => $request->input('isActive'),
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