<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Str;
use App\Models\CPTCode\CPTCode;
use Illuminate\Support\Facades\DB;
use App\Transformers\CPTCode\CPTCodeTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class CPTCodeService
{
    public function listCPTCode($request, $id)
    {

        try {
            if (!empty($id)) {
                $data = CPTCode::with('provider', 'service', 'duration')->where("cptcodes.id", $id)->orderBy('createdAt', 'DESC')->paginate(env('PER_PAGE', 20));
            } else {
                $data = CPTCode::with('provider', 'service', 'duration')->orderBy('createdAt', 'DESC')->paginate(env('PER_PAGE', 20));
            }
            return fractal()->collection($data)->transformWith(new CPTCodeTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createCPTCode($request)
    {
            $udid = $request->input('serviceId');
            $service = Helper::tableName('App\Models\CPTCode\Service', $udid);
            $udid = Str::uuid()->toString();
            $serviceId = $service;
            $providerId = $request->input('providerId');
            $name = $request->input('name');
            $billingAmout = $request->input('billingAmout');
            $description = $request->input('description');
            $durationId = $request->input('durationId');
            DB::select('CALL createCPTCode("' . $udid . '","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","' . $durationId . '")');
            return response()->json(['message' => trans('messages.createdSuccesfully')],  200);
            // $cptCodeData = CPTCode::where('udid', $udid)->first();
            // dd($cptCodeData);
            // $message = ['message' => trans('messages.createdSuccesfully')];
            // $resp =  fractal()->item($cptCodeData)->transformWith(new CPTCodeTransformer())->toArray();
            
            // $endData = array_merge($message, $resp);
            // return $endData;
       
    }

    public function updateCPTCode($request, $id)
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
            DB::select('CALL updateCPTCode("' . $id . '","' . $serviceId . '","' . $providerId . '","' . $name . '","' . $billingAmout . '","' . $description . '","' . $durationId . '","' . $updatedBy . '","' . $isActive . '")');
            $cptCodeData = CPTCode::where('id', $id)->first();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $resp =  fractal()->item($cptCodeData)->transformWith(new CPTCodeTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateCPTCodeStatus($request, $id)
    {
        try {
            if ($request->input('isActive')) {
                $isActive = $request->input('isActive');
            } else {
                $isActive = 0;
            }
            CPTCode::where("id", $id)->update(["isActive" => $isActive]);
            $cptCodeData = CPTCode::where('id', $id)->first();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $resp =  fractal()->item($cptCodeData)->transformWith(new CPTCodeTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteCPTCode($request, $id)
    {
        try {
            $CPTCode = CPTCode::where('udid', $id)->first();
            $input = ['deletedBy' => 1, 'isActive' => 0, 'isDelete' => 1];
            CPTCode::where('udid', $id)->update($input);
            CPTCode::where('udid', $id)->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
