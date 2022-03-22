<?php

namespace App\Services\Api;

use Exception;
use App\Models\BitrixField\BitrixField;
use App\Transformers\BitrixField\BitrixFieldTransformer;

class BitrixService
{
    public function bitrixFiledGet($request, $id)
    {
        if ($id) {
            $data =  BitrixField::where("id", $id)->where("isDelete", "0")->first();
            if (!empty($data)) {
                return fractal()->item($data)->transformWith(new BitrixFieldTransformer())->toArray();
            } else {
                return response()->json(['message' => "Record not found."], 500);
            }
        } else {
            $data =  BitrixField::where("isDelete", "0")->get();
            return fractal()->collection($data)->transformWith(new BitrixFieldTransformer())->toArray();
        }
    }

    public function bitrixFieldCreate($request)
    {
        if ($request->input('bitrixId')) {
            $bitrixId = $request->input('bitrixId');
        } else {
            return response()->json(['message' => "bitrixId is required."], 500);
        }

        if ($request->input('patientId')) {
            $patientId = $request->input('patientId');
        } else {
            return response()->json(['message' => "patientId is required."], 500);
        }

        $lastid =   BitrixField::insertGetId([
            "bitrixId" => $bitrixId,
            "patientId" => $patientId,
            "isActive"  => 1
        ]);

        if ($lastid) {
            $serviceData = BitrixField::where('id', $lastid)->first();
            $message = ['message' => trans('messages.createdSuccesfully')];
            $resp =  fractal()->item($serviceData)->transformWith(new BitrixFieldTransformer())->toArray();
            $endData = array_merge($message, $resp);
            return $endData;
        } else {
            return response()->json(['message' => "bitrixField not inserted,Something wroung."], 500);
        }
    }

    public function bitrixFieldUpdate($request, $id)
    {
        try {
            if ($request->input('bitrixId')) {
                $bitrixData["bitrixId"] = $request->input('bitrixId');
            }

            if ($request->input('patientId')) {
                $bitrixData["patientId"] = $request->input('patientId');
            }

            $bitrixData["updatedBy"] = 1;
            $bitrixData["isActive"] = 1;

            BitrixField::where("id", $id)->update($bitrixData);
            $bitrixFieldData = BitrixField::where('id', $id)->first();
            if (!empty($bitrixFieldData)) {
                $message = ['message' => trans('messages.updatedSuccesfully')];
                $resp =  fractal()->item($bitrixFieldData)->transformWith(new BitrixFieldTransformer())->toArray();
                $endData = array_merge($message, $resp);
                return $endData;
            } else {
                return response()->json(['message' => "Record not found."], 500);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function bitrixFieldDelete($request, $id)
    {
        try {
            $input  =   [
                'deletedBy' =>  1,
                'isActive'  =>  0,
                'isDelete'  =>  1
            ];

            $bitrixFieldData = BitrixField::where('id', $id)->first();

            if (!empty($bitrixFieldData)) {
                BitrixField::where('id', $id)->update($input);
                return response()->json(['message' => trans('messages.deletedSuccesfully')],  200);
            } else {
                return response()->json(['message' => "Record not found."], 500);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
