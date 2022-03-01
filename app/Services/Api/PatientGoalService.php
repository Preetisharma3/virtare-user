<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Patient\Patient;
use App\Models\Patient\PatientGoal;
use App\Transformers\Patient\PatientGoalTransformer;

class PatientGoalService
{
    public function index($request, $id, $goalId)
    {
        if ($id) {
            if ($goalId) {
                $patient = Helper::entity('patient', $id);
                $data = PatientGoal::where([['patientId', $patient->id], ['id', $goalId]])->get();
            } elseif (!$goalId) {
                $patient = Patient::where('udid', $id)->first();
                $data = PatientGoal::where('patientId', $patient->id)->get();
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
