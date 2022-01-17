<?php

namespace App\Http\Controllers\Api\v1;
use App\Services\Api\ScreenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function createScreen(Request $request){

        return (new ScreenService)->addScreen($request);
    }

    public function getScreen(Request $request){
          
        return (new ScreenService)->getScreenList($request);
    }
}
