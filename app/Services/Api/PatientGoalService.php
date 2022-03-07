<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use Illuminate\Support\Str;
use App\Models\Patient\PatientGoal;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Patient\PatientGoalTransformer;

class PatientGoalService
{
    public function index($request, $id, $goalId)
    {
        if ($id) {
            $patient = Helper::entity('patient', $id);
            if ($goalId) {
                $access=Helper::haveAccess($patient);
                if($access){
                    $data = PatientGoal::where([['patientId', $patient], ['id', $goalId]])->get();
                }
            } elseif (!$goalId) {
                $data = PatientGoal::where('patientId', $patient)->get();
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

    public function patientGoalAdd($request,$id){
        try{
            $patient=Helper::entity('patient',$id);
            $input=['lowValue'=>$request->input('lowValue'),'highValue'=>$request->input('highValue'),
            'createdBy'=>Auth::id(),'patientId'=>$patient,'udid'=>Str::uuid()->toString()];
            PatientGoal::create($input);
            return response()->json(['message'=>trans('messages.createdSuccesfully')]);
        }catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
