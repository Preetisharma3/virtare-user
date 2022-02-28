<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Transformers\Patient\PatientCountTransformer;

class DashboardService
{
    public function count($request)
    {
        try {
            $timelineId = $request->timelineId;

            $total = DB::select(
                'CALL getTotalPatientsCount()',
            );
            $count = DB::select(
                'CALL getPatientConditionsCount(' . $timelineId . ')',
            );
            $patient = DB::select(
                'CALL getPatientsCount()',
            );
            $data = array_merge(
                $total,
                $count,
                $patient
            );
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function staffNetwork($request)
    {
        try {
            $timelineId = $request->timelineId;
            $data = DB::select(
                'CALL getStaffNeworkCount(' . $timelineId . ')',
            );
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function staffSpecialization($request)
    {
        try {
            $timelineId = $request->timelineId;
            $data = DB::select(
                'CALL getStaffSpecializationCount(' . $timelineId . ')',
            );
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
