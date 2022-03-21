<?php

namespace App\Services\Api;

use Exception;
use App\Models\Vital\VitalTypeField;
use App\Transformers\Vital\VitalTypeFieldTransformer;

class VitalService
{

    public function VitalTypeFieldList($request, $id)
    {
        try {
            if ($id) {
                $getVital = VitalTypeField::where('vitalTypeId', $id)->get();
                return fractal()->collection($getVital)->transformWith(new VitalTypeFieldTransformer())->toArray();
            } else {
                $getVital = VitalTypeField::get();
                return fractal()->collection($getVital)->transformWith(new VitalTypeFieldTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
