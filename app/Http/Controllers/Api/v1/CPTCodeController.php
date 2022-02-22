<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\CPTCodeService;
use Illuminate\Http\Request;

class CPTCodeController extends Controller
{

   public function listCPTCode(Request $request)
   {
    return (new CPTCodeService)->listCPTCode($request); 
   }

    public function createCPTCode(Request $request)
    {
        return (new CPTCodeService)->createCPTCode($request);
    }

    public function updateCPTCode(Request $request , $id)
    {
        return (new CPTCodeService)->updateCPTCode($request,$id);
    }

    public function deleteCPTCode(Request $request , $id)
    {
        return (new CPTCodeService)->deleteCPTCode($request,$id);
    }
    
}
