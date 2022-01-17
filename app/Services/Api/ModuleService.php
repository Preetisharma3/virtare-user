<?php

namespace App\Services\Api;

use Exception;
use App\Models\Module\Module;
use App\Transformers\Module\ModuleTransformer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ModuleService
{
    public function addModule($request)
    {
        try {
            $module = [
                'name' => $request->name,
                'description' => $request->description,
                'createdBy' => 1
            ];
             Module::create($module);
           return response()->json(['message' =>'Created Successfully'],200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getModuleList($request)
    {
        try {
             $module = Module::all();
             return fractal()->collection($module)->transformWith(new ModuleTransformer())->toArray();
        }catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }
}
