<?php

namespace App\Services\Api;

use Exception;
use App\Models\Program\Program;
use Illuminate\Support\Str;
use App\Transformers\Program\ProgramTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProgramService
{
    public function programList($request, $id)
    {
        try {
            if (!$id) {
                if ($request->all()) {
                    $getProgram = Program::where('isActive',1)->with('type')->get();
                    return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->toArray();
                } else {
                    if ($request->active) {
                        $getProgram = Program::with('type')->paginate(env('PER_PAGE', 20));
                    }else{
                    $getProgram = Program::where('isActive',1)->with('type')->paginate(env('PER_PAGE', 20));
                    }
                    return fractal()->collection($getProgram)->transformWith(new ProgramTransformer())->paginateWith(new IlluminatePaginatorAdapter($getProgram))->toArray();
                }
            } else {
                $program = Program::where('udid', $id)->get();
                return fractal()->collection($program)->transformWith(new ProgramTransformer())->toArray();
            }
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

    public function updateProgram($request, $id)
    {
        try {
            $program = array();
            if (!empty($request->input('typeId'))) {
                $program['typeId'] =  $request->input('typeId');
            }
            if (!empty($request->input('description'))) {
                $program['description'] =  $request->input('description');
            }
            if (!empty($request->input('name'))) {
                $program['name'] =  $request->input('name');
            }
            if (empty($request->input('status'))) {
                $program['isActive'] =  0;
            } else {
                $program['isActive'] = 1;
            }
            $program['updatedBy'] =  1;

            if (!empty($program)) {
                Program::where('udid', $id)->update($program);
            }
            $newData = Program::where('udid', $id)->first();
            $message = ["message" => "updated Successfully"];
            $resp =  fractal()->item($newData)->transformWith(new ProgramTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteProgram($request, $id)
    {
        try {
            $program = Program::where('udid', $id)->first();
            $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
            Program::where('udid', $id)->update($input);
            Program::where('udid', $id)->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
