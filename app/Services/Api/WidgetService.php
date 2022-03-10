<?php

namespace App\Services\Api;

use App\Models\AccessRole\AccessRole;
use App\Models\Widget\Widget;
use Illuminate\Support\Str;
use App\Transformers\Widget\WidgetTransformer;
use App\Models\Dashboard\DashboardWidgetByRole;
use App\Models\Widget\WidgetAccess;
use App\Transformers\Widget\WidgetUpdateTransformer;
use App\Transformers\Widget\AssignedWidgetTransformer;
use App\Transformers\Widget\WidgetAccessTransformer;
use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WidgetService
{
    public function getWidget()
    {
        $data = Widget::all();
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
        return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
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

    public function listWidgetAccess($request,$id)
    {
        try{
            $role = AccessRole::where('udid',$id)->first();
            $data = WidgetAccess::where('accessRoleId',$role->id)->with('widget')->get();
        return fractal()->collection($data)->transformWith(new WidgetAccessTransformer())->toArray();
     }catch (Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);  
       }
    }

    public function createWidgetAccess($request,$id)
    {
        try{
            $role = AccessRole::where('udid',$id)->first();

            $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1,'deletedAt'=>Carbon::now()];
            WidgetAccess::where('accessRoleId', $role->id)->update($input);

            $widget = $request->widgets;
            foreach($widget as $widgetId ){
                $widgets = [
                    'udid' => Str::uuid()->toString(),
                    'accessRoleId' => $role->id,
                    'widgetId' => $widgetId,
                ];
                WidgetAccess::create($widgets);
            }
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);  
           }
    }

    public function deleteWidgetAccess($request,$id)
    {
        try {
            $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
            WidgetAccess::where('udid', $id)->update($input);
            WidgetAccess::where('udid', $id)->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
