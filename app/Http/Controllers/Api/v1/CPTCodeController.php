<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CPTCodeService;
use App\Services\Api\ExcelGeneratorService;

class CPTCodeController extends Controller
{

   public function listCPTCode(Request $request, $id = NULL)
   {
    return (new CPTCodeService)->listCPTCode($request,$id); 
   }

    public function createCPTCode(Request $request)
    {
        return (new CPTCodeService)->createCPTCode($request);
    }

    public function updateCPTCode(Request $request , $id)
    {
        return (new CPTCodeService)->updateCPTCode($request,$id);
    }

    public function updateCPTCodeStatus(Request $request , $id)
    {
        return (new CPTCodeService)->updateCPTCodeStatus($request,$id);
    }

    public function deleteCPTCode(Request $request , $id)
    {
        return (new CPTCodeService)->deleteCPTCode($request,$id);
    }

    public function cptCodeReport(){
        ExcelGeneratorService::excelCptCodeExport();
    }
    
}
