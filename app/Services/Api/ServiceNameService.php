<?php

namespace App\Services\Api;

use App\Models\CPTCode\Service;
use App\Transformers\CPTCode\ServiceTransformer;
use Exception;
use Illuminate\Support\Str;

class ServiceNameService
{
   public function listService($request,$id)
   {
    
       try{
        if(!empty($id))
        {
            $data = Service::find($id);
            return fractal()->item($data)->transformWith(new ServiceTransformer())->toArray();
        }
        else
        {
            $data = Service::orderBy('createdAt', 'DESC')->get();
        }
        return fractal()->collection($data)->transformWith(new ServiceTransformer())->toArray();
    }catch(Exception $e){
        return response()->json(['message' => $e->getMessage()], 500);    
    } 
   }

    public function createService($request)
    {
        try {
            $udid = Str::uuid()->toString();
            if($request->input('serviceName')){
                $serviceName = $request->input('serviceName');
            }else{
                return response()->json(['message' => "service name is required."], 500); 
            }

            $isActive = 1;
            // DB::select('CALL createCPTCode("' . $udid . '","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","'.$durationId.'")');
            Service::insert([
                "udid" => $udid,
                "name" => $serviceName,
                "isActive" => $isActive
            ]);
            $serviceData = Service::where('udid', $udid)->first();
            $message = ['message' => trans('messages.createdSuccesfully')];
            $resp =  fractal()->item($serviceData)->transformWith(new ServiceTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateService($request,$id)
    {
        try {
            if($request->input('serviceName')){
                $serviceName = $request->input('serviceName');
            }

            $updatedBy = 1;
            $isActive = 1;
            // DB::select('CALL updateCPTCode("'.$id.'","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","'.$durationId.'","'.$updatedBy.'","'.$isActive.'")');
            Service::where("id",$id)->update([
                "name" => $serviceName,
                "isActive" => $isActive,
                "updatedBy" => $updatedBy
            ]);

            $serviceData = Service::where('id', $id)->first();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $resp =  fractal()->item($serviceData)->transformWith(new ServiceTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData; 
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteService($request,$id)   
    {
        try {
            $serviceData = Service::where('udid', $id)->first();
            $input=['deletedBy'=>1,'isActive'=>0,'isDelete'=>1];
            Service::where('udid', $id)->update($input);
            return response()->json(['message' => trans('messages.deletedSuccesfully')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
