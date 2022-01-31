<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\DB;
use App\Transformers\Patient\PatientCountTransformer;



class TimelineService
{
    public function patientTotal($request){
        $timelineId =  $request->timelineId;
            $data = DB::select(
                'CALL getTotalPatientSummaryCount('.$timelineId.')',
             );
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    public function appointmentTotal($request){
        $timelineId =  $request->timelineId;
            $data = DB::select(
                'CALL getTotalAppointmentSummaryCount('.$timelineId.')',
             );
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }
}
