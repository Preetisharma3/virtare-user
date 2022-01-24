<?php

namespace App\Services\Api;

use Exception;
use App\Models\ScreenAction\ScreenAction;
use App\Transformers\ScreenAction\ScreenActionTransformer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ScreenActionService
{
    public function addScreenAction($request)
    {
        try {
            $screen_actions = $request->post('acreen_action');

            foreach ($screen_actions as $action) {
                $acreenAction = [
                    'actionId' => $action,
                    'userId'   => Auth::id(),
                ];
                ScreenAction::create($acreenAction);
            }
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getScreenActionList($request)
    {
        try {
            $user_id = auth()->user()->id;
            $action = ScreenAction::where('userId', $user_id)->with('action', 'user')->get();
            return fractal()->collection($action)->transformWith(new ScreenActionTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
