<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment\Appointment;
use App\Transformers\Staff\StaffNetworkTransformer;
use App\Transformers\Patient\PatientCountTransformer;
use App\Transformers\Dashboard\CountPerMonthTransformer;
use App\Transformers\Staff\StaffSpecializationTransformer;

class DashboardService
{
    public function count()
    {
        try {
            $total = DB::select(
                'CALL getTotalPatientsCount()',
             );
             $count = DB::select(
                'CALL getPatientConditionsCount()',
            );
            $patient = DB::select(
                'CALL getPatientsCount()',
             );
            
            
             $data = array_merge(
                 $patient,$count,$total
             );

            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
   

   

    


    public function staffNetwork()
    {
        $data = Staff::with('network')->select('networkId', DB::raw('count(*) as count'))->groupBy('networkId')->get();
        return fractal()->collection($data)->transformWith(new StaffNetworkTransformer())->toArray();
    }

    public function staffSpecialization()
    {
        $data = Staff::with('specialization')->select('specializationId', DB::raw('count(*) as count'))->groupBy('specializationId')->get();
        return fractal()->collection($data)->transformWith(new StaffSpecializationTransformer())->toArray();
    }

    public function patientCountMonthly()
    {
        $data = Patient::select(DB::raw('count(*) as count'), DB::raw("DATE_FORMAT(createdAt, '%M') month"),  DB::raw('YEAR(createdAt) year'))->groupby('year', 'month')->get();
        $patientData = Helper::dateGroup($data, 'year');
        return fractal()->collection($patientData)->transformWith(new CountPerMonthTransformer())->toArray();
    }

    public function appointmentCountMonthly(){
        $data = Appointment::select(DB::raw('count(*) as count'), DB::raw("DATE_FORMAT(startTime, '%M') month"),  DB::raw('YEAR(startTime) year'))->groupby('year', 'month')->get();
        $appointmentData = Helper::dateGroup($data, 'year');
        return fractal()->collection($appointmentData)->transformWith(new CountPerMonthTransformer())->toArray();
    }
}
