<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\ScreenActionService;
use Illuminate\Http\Request;

class ScreenActionController extends Controller
{
    public function creatScreenAction(Request $request)
    {
        return (new ScreenActionService)->addScreenAction($request);
    }

    public function getScreenAction(Request $request)
    {
        return (new ScreenActionService)->getScreenActionList($request);
    }
}
