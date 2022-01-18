<?php

namespace App\Services\Api;

use App\Models\Widget\Widget;
use App\Transformers\Widget\WidgetTransformer;
use App\Models\Dashboard\DashboardWidgetByRole;
use App\Transformers\Widget\StaffWidgetTransformer;
use App\Transformers\Widget\WidgetUpdateTransformer;
use App\Transformers\Widget\AssignedWidgetTransformer;

class WidgetService
{
    public function getWidget()
    {
        $data = Widget::with('widgetType')->get();
        return fractal()->collection($data)->transformWith(new WidgetTransformer())->toArray();
    }

    public function assignWidget($request)
    {
        $input = [
            'widgetId' => $request->widgetId,
            'widgetPath' => $request->widgetPath,
            'roleId' => $request->roleId,
            'canNotViewModifyOrDelete' => $request->canNotViewModifyOrDelete,
            'createdBy'=>1
        ];
        DashboardWidgetByRole::create($input);
        return response()->json(['message' => 'Created Successfully'], 200);
    }

    public function getAssignedWidget(){
        $data = DashboardWidgetByRole::with('widget','widgetType','role')->where('canNotViewModifyOrDelete',0)->get();
        return fractal()->collection($data)->transformWith(new AssignedWidgetTransformer())->toArray();
    }

    public function updateWidget($request,$id){
        $data = DashboardWidgetByRole::findOrFail($id);
        $data->update($request->all());
        return fractal()->item($data)->transformWith(new WidgetUpdateTransformer())->toArray();
    }
}
