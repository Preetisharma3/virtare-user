<?php

namespace App\Services\Api;

use Exception;
use App\Models\ScreenAction\ScreenAction;
use App\Transformers\ScreenAction\ScreenActionTransformer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScreenActionService
{
    public function addScreenAction($request)
    {
        try {

         $userId = $request->userId;
         $actionId = $request->actionId;
         $deviceId = $request->deviceId;
         DB::select('CALL createScreenAction('.$userId.','.$actionId.','.$deviceId.')');
             
            return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
