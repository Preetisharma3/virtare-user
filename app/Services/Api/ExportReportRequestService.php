<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ExportReportRequest\ExportReportRequest;
use App\Transformers\ExportReportRequest\ExportReportRequestTransformer;

class ExportReportRequestService
{
    public function insertExportRequest($request)
    {
        try {

            if($request->input('reportType'))
            {
                $reportType = $request->input('reportType');
            }
            else
            {
                return response()->json(['message' => "reportType is required."], 500); 
            }

            $userId = Auth::id();
            $udid = Str::uuid()->toString();

            $input  =   [
                'deletedBy' =>  1,
                'isActive'  =>  0,
                'isDelete'  =>  1
            ];

            ExportReportRequest::where("userId",$userId)->update($input);

            $lastid =  ExportReportRequest::insertGetId([
                "reportType" => $reportType,
                "userId" => $userId,
                "udid" => $udid,
                "isActive"  => 1
            ]);

            if($lastid)
            {
                $serviceData = ExportReportRequest::where('id', $lastid)->first();
                $message = ['message' => trans('messages.createdSuccesfully')];
                $resp =  fractal()->item($serviceData)->transformWith(new ExportReportRequestTransformer())->toArray();
                $endData = array_merge($message, $resp);
                return $endData;
                // return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
            }else{
                return response()->json(['message' => "server internal error."], 500);
            }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    public function checkReportRequest($id = "")
    {
        if($id)
        {
            $resultData = ExportReportRequest::where('udid', $id)->where('isActive', "1")->first();
            if(!empty($resultData))
            {
                return true;
            }
            else
            {
                return false;    
            }
        }
        else
        {
            return false;
        }
    }
}
