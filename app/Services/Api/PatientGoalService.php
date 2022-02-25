<?php

namespace App\Services\Api;

use App\Models\Device\Device;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientGoal;
use App\Models\Patient\PatientInventory;
use App\Transformers\Patient\PatientGoalTransformer;
use App\Transformers\Patient\PatientInventoryTransformer;

class PatientGoalService
{
    public function index($request, $id, $goalId)
    {
        if ($id) {
            if ($goalId) {
                $data = PatientGoal::where([['patientId', $id], ['id', $goalId]])->get();
            } elseif (!$goalId) {
                $data = PatientGoal::where('patientId', $id)->get();
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
        } elseif (!$id) {
            if ($goalId) {
                $data = PatientGoal::where([['patientId', auth()->user()->patient->id], ['id', $goalId]])->get();
            } elseif (!$goalId) {
                $data = PatientGoal::where('patientId', auth()->user()->patient->id)->get();
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
        }
        return  fractal()->collection($data)->transformWith(new PatientGoalTransformer())->toArray();
    }
}
