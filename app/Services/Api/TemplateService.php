<?php

namespace App\Services\Api;

use App\Models\Template\Template;
use App\Transformers\Template\TemplateTransformer;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TemplateService
{

    public function listTemplate($request,$id)
    {
        try {
            if(!$id){
                $template = Template::all();
                return fractal()->collection($template)->transformWith(new TemplateTransformer())->toArray();
            }else{
                $template = Template::where('udid',$id)->get();
                return fractal()->collection($template)->transformWith(new TemplateTransformer())->toArray();
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function createTemplate($request)
    {
        try {
            $template = [
                'udid' => Str::uuid()->toString(),
                'name' => $request->input('name'),
                'dataType' => $request->input('dataType'),
                'templateType' => $request->input('templateType'),
                'isActive' => $request->input('isActive')
            ];
            $newtemplate = Template::create($template);
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function updateTemplate($request,$id)
    {
        try {
            $template = array();
        if(!empty($request->input('name'))){
            $template['name'] =  $request->input('name');
        }
        if(!empty($request->input('dataType'))){
            $template['dataType'] =  $request->input('dataType');
        }
        if(!empty($request->input('templateType'))){
            $template['templateType'] =  $request->input('templateType');
        }
        if(empty($request->input('isActive'))){
            $template['isActive'] =  0;
        }else{
            $template['isActive'] = 1;
        }
        $template['updatedBy'] =  Auth::id();
        
        if(!empty($template)){
            Template::where('udid', $id)->update($template);
        }
        return response()->json(['message' => trans('messages.updatedSuccesfully')]);
        
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function deleteTemplate($request,$id)
    {
        try {
            $temp = Template::where('udid',$id)->first();
            $id = $temp->id;
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            Template::find($id)->update($data);
            Template::find($id)->delete();
            return response()->json(['message' => 'delete successfully']);
       } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
       }
    }
}
