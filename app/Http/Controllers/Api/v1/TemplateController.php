<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\TemplateService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
   
    public function listTemplate(Request $request)
    {
        return (new TemplateService())->listTemplate($request);  
    } 

    public function createTemplate(Request $request)
    {
        return (new TemplateService())->createTemplate( $request);
    }

    public function updateTemplate(Request $request,$id)
    {
        return (new TemplateService())->updateTemplate( $request,$id);
    }

    public function deleteTemplate(Request $request,$id)
    {
        return (new TemplateService())->deleteTemplate( $request,$id);
    }
}
