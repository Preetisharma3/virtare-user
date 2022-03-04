<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\WidgetService;
use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    public function getWidget()
    {
        return (new WidgetService)->getWidget();
    }

    public function assignwidget(Request $request)
    {
        return (new WidgetService)->assignwidget($request);
    }

    public function getassignedWidget()
    {
        return (new WidgetService)->getassignedWidget();
    }


    public function updateWidget(request $request,$id)
    {
        return (new WidgetService)->updateWidget($request,$id);
    }
    
    public function createWidgetAccess(Request $request,$id)
    {
        return (new WidgetService)->createWidgetAccess($request,$id);
    }

    public function deleteWidgetAccess(Request $request,$id)
    {
        return (new WidgetService)->deleteWidgetAccess($request,$id);
    }
}
