<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
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
                $access = Helper::haveAccess($patient);
                if ($access) {
                    $data = PatientGoal::where([['patientId', $patient], ['udid', $goalId]])->get();
                }
            } elseif (!$goalId) {
                $data = PatientGoal::where('patientId', $patient)->get();
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
        } elseif (!$id) {
            if ($goalId) {
                $data = PatientGoal::where([['patientId', auth()->user()->patient->id], ['udid', $goalId]])->get();
            } elseif (!$goalId) {
                $data = PatientGoal::where('patientId', auth()->user()->patient->id)->get();
            } else {
                return response()->json(['message' => trans('messages.unauthenticated')], 401);
            }
        }
        return  fractal()->collection($data)->transformWith(new PatientGoalTransformer())->toArray();
    }

    public function patientGoalAdd($request, $id)
    {
        try {
            $patient = Helper::entity('patient', $id);
            $startDate=Helper::dateOnly($request->input('startDate'));
            $endDate=Helper::dateOnly($request->input('endDate'));
            $input = [
                'lowValue' => $request->input('lowValue'), 'highValue' => $request->input('highValue'),'vitalFieldId'=>$request->input('vitalField'),
                'startDate'=>$startDate,'endDate'=>$endDate,'frequency'=>$request->input('frequency'),
                'frequencyTypeId'=>$request->input('frequencyType'),'deviceTypeId'=>$request->input('deviceType'),
                'createdBy' => Auth::id(), 'patientId' => $patient, 'udid' => Str::uuid()->toString()
            ];
           $goal= PatientGoal::create($input);
           $note=['udid'=>Str::uuid()->toString(),'referenceId'=>$goal->id,'entityType'=>'patientGoal','note'=>$request->input('note')];
           Note::create($note);
            return response()->json(['message' => trans('messages.createdSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function patientGoalDelete($request, $id, $goalId)
    {
        try {
            $input = [
                'deletedBy' => Auth::id(), 'isDelete' => 1,'isActive' => 0
            ];
            PatientGoal::where('udid',$goalId)->update($input);
            PatientGoal::where('udid',$goalId)->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
