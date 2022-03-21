<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\DB;
use App\Transformers\Patient\NewPatientCountTransformer;

class TimelineService
{
    public function patientTotal($request)
    {
        $timelineId =  $request->timelineId;
        $data = DB::select(
            'CALL getTotalPatientSummaryCount(' . $timelineId . ')',
        );
        return fractal()->collection($data)->transformWith(new NewPatientCountTransformer())->toArray();
    }

    public function appointmentTotal($request)
    {
        $timelineId =  $request->timelineId;
        $data = DB::select(
            'CALL getTotalAppointmentSummaryCount(' . $timelineId . ')',
        );
        return fractal()->collection($data)->transformWith(new NewPatientCountTransformer())->toArray();
    }
}
