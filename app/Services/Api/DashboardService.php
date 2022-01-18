<?php

namespace App\Services\Api;

use Exception;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientCondition;
use App\Transformers\Patient\PatientCountTransformer;
use App\Transformers\Communication\CallStatusTransformer;
use App\Transformers\Patient\PatientConditionTransformer;
use App\Transformers\Patient\PatientConditionCountTransformer;

class DashboardService
{
   public function count($request)
    {
        try {
            $count = Patient::count();
            $text = "totalPatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function activePatients()
    {
        try {
            $count = Patient::where('isActive', 1)->count();
            $text = "activePatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function inActivePatients()
    {
        try {
            $count = Patient::where('isActive', 0)->count();
            $text = "inactivePatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function newPatients()
    {
        try {
            $count = Patient::whereDoesntHave('vitals')->count();
            $text = "newPatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function abnormalPatients()
    {
        try {
            $count = Patient::whereHas('conditions', function ($query) {
                $query->where('conditionId', 79);
            })->count();
            $text = "abnormalPatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function criticalPatients()
    {
        try {
            $count = Patient::whereHas('conditions', function ($query) {
                $query->where('conditionId',80);
            })->count();
            $text = "criticalPatients";
            $data = [
                "count" => $count,
                "text" => $text
            ];
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function patientCondition(){
        $data =PatientCondition::with('condition')->select('conditionId', DB::raw('count(*) as count'))->groupBy('conditionId')->get();
        // return $data;
        return fractal()->collection($data)->transformWith(new PatientConditionCountTransformer())->toArray(); 
    }
    

}