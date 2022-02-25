<?php

namespace App\Services\Api;

use Exception;
use App\Models\Screen\Screen;
use App\Transformers\Screen\ScreenTransformer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ScreenService
{
    public function addScreen($request)
    {
        try {
            $screen = [
                'name' => $request->name,
                'moduleId'=>$request->moduleId,
                'createdBy' => 1
            ];
             Screen::create($screen);
           return response()->json(['message' => trans('messages.createdSuccesfully')],200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getScreenList($request)
    {
        try {
             $screen = Screen::all();
             return fractal()->collection($screen)->transformWith(new ScreenTransformer())->toArray();
        }catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }
}
