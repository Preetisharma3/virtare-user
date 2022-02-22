<?php

namespace App\Services\Api;

use App\Models\CPTCode\CPTCode;
use App\Models\GlobalCode\GlobalCode;
use App\Transformers\CPTCode\CPTCodeTransformer;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CPTCodeService
{
   public function listCPTCode()
   {
       try{
        $data = CPTCode::with('provider','service','duration')->get();
        return fractal()->collection($data)->transformWith(new CPTCodeTransformer())->toArray();
    }catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);    
    } 
   }

    public function createCPTCode($request)
    {
        try {
            $udid = Str::random(10);
            $serviceId = $request->input('serviceId');
            $providerId = $request->input('providerId');
            $name = $request->input('name');
            $billingAmout = $request->input('billingAmout');
            $description = $request->input('description');
            $durationId = $request->input('durationId');
            DB::select('CALL createCPTCode("' . $udid . '","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","'.$durationId.'")');
            
            $cptCodeData = CPTCode::where('udid', $udid)->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($cptCodeData)->transformWith(new CPTCodeTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateCPTCode($request,$id)
    {
        try {
            $serviceId = $request->input('serviceId');
            $providerId = $request->input('providerId');
            $name = $request->input('name');
            $billingAmout = $request->input('billingAmout');
            $description = $request->input('description');
            $durationId = $request->input('durationId');
            $updatedBy = 1;
            $isActive = 1;
            DB::select('CALL updateCPTCode("'.$id.'","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","'.$durationId.'","'.$updatedBy.'","'.$isActive.'")');
            $cptCodeData = CPTCode::where('id', $id)->first();
            $message = ["message" => "created Successfully"];
            $resp =  fractal()->item($cptCodeData)->transformWith(new CPTCodeTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteCPTCode($request,$id)   
    {
        try {
            CPTCode::where('id', $id)->delete();
            return response()->json(['message' => "Deleted Successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
