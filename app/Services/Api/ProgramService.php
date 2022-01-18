<?php

namespace App\Services\Api;

use Exception;
use App\Models\Program\Program;
use App\Transformers\Program\ProgramTransformer;

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
}