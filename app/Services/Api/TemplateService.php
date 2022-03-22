<?php

namespace App\Services\Api;

use App\Models\Template\Template;
use App\Transformers\Template\TemplateTransformer;
use Exception;
use Illuminate\Support\Str;

class TemplateService
{

    public function listTemplate()
    {
        try {
            $template = Template::all();
            return fractal()->collection($template)->transformWith(new TemplateTransformer())->toArray();
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
                'templateType' => $request->input('templateType')
            ];
            $newtemplate = Template::create($template);
            $newtemplate = Template::where('udid', $newtemplate->udid)->first();
            $message = ['message' => trans('messages.createdSuccesfully')];
            $resp =  fractal()->item($newtemplate)->transformWith(new TemplateTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function updateTemplate($request, $id)
    {
        try {
            $template = [
                'name' => $request->input('name'),
                'dataType' => $request->input('dataType'),
                'templateType' => $request->input('templateType')
            ];
            $newtemplate = Template::find($id)->update($template);
            $newtemplate = Template::where('id', $id)->first();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $resp =  fractal()->item($newtemplate)->transformWith(new TemplateTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function deleteTemplate($request, $id)
    {
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            Template::find($id)->update($data);
            Template::find($id)->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
