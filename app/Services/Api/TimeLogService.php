<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\CPTCode\CPTCode;
use App\Models\Note\Note;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient\PatientTimeLog;
use App\Transformers\Patient\PatientTimeLogTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TimeLogService
{
    public function timeLogList($request, $id)
    {
        if (!$id) {
            if ($request->all) {
                if($request->toDate || $request->fromDate){
                    $fromDate=Helper::dateOnly($request->fromDate);
                    $toDate=Helper::dateOnly($request->toDate);
                    $data = PatientTimeLog::where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('category', 'logged', 'performed', 'notes')->get();
                }else{
                    $data = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->get();
                }
                return fractal()->collection($data)->transformWith(new PatientTimeLogTransformer())->toArray();
            } else {
                if($request->toDate || $request->fromDate){
                    $fromDate=Helper::dateOnly($request->fromDate);
                    $toDate=Helper::dateOnly($request->toDate);
                    $data = PatientTimeLog::where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('category', 'logged', 'performed', 'notes')->paginate(env('PER_PAGE', 20));
                }else{
                    $data = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->paginate(env('PER_PAGE', 20));
                }
                return fractal()->collection($data)->transformWith(new PatientTimeLogTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            }
        } else {
            $data = PatientTimeLog::where('udid', $id)->with('category', 'logged', 'performed', 'notes')->first();
            return fractal()->item($data)->transformWith(new PatientTimeLogTransformer())->toArray();
        }
    }

    public function timeLogUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->input('noteId')) {
                $noteData = ['note' => $request->input('note'), 'updatedBy' => Auth::id()];
                Note::where('id', $request->input('noteId'))->update($noteData);
            } else {
                $timeLog = PatientTimeLog::where('udid', $id)->first();
                $noteData = [
                    'note' => $request->input('note'), 'entityType' => 'auditlog', 'referenceId' => $timeLog->id,
                    'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id(), 'categoryId' => 155, 'type' => 153
                ];
                Note::create($noteData);
            }
           
            $input = ['timeAmount' => $request->input('timeAmount'), 'updatedBy' => Auth::id()];
            PatientTimeLog::where('udid', $id)->update($input);
            $data = PatientTimeLog::where('udid', $id)->with('category', 'logged', 'performed', 'patient.notes')->first();
            $userdata = fractal()->item($data)->transformWith(new PatientTimeLogTransformer())->toArray();
            $message = ['message' => trans('messages.updatedSuccesfully')];
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function timeLogDelete($request, $id)
    {
        $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
        $data = PatientTimeLog::where('udid', $id)->update($input);
        PatientTimeLog::where('udid', $id)->delete();
        return response()->json(['message' => trans('messages.deletedSuccesfully')]);
    }

    // Add TimeLog
    public function patientTimeLogAdd($request, $entityType, $id, $timelogId)
    {
        DB::beginTransaction();
        // try {
            if (!$timelogId) {
                $dateConvert = Helper::date($request->input('date'));
                $patientId = Patient::where('udid', $id)->first();
                $performedBy = Helper::entity('staff', $request->input('performedBy'));
                $loggedBy = Helper::entity('staff', $request->input('loggedBy'));
                $cpt=CPTCode::where('udid',$request->cptCode)->first();
                $input = [
                    'categoryId' => $request->input('category'), 'loggedId' => $loggedBy, 'udid' => Str::uuid()->toString(),
                    'performedId' => $performedBy, 'date' => $dateConvert, 'timeAmount' => $request->input('timeAmount'),
                    'createdBy' => Auth::id(), 'patientId' => $patientId->id,'cptCodeId'=>$cpt->id
                ];
                $data = PatientTimeLog::create($input);
                if ($request->input('note')) {
                    $note = [
                        'note' => $request->input('note'), 'entityType' => 'auditlog', 'referenceId' => $data->id, 'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id()
                    ];
                    Note::create($note);
                }
                $data = response()->json(['message' => trans('messages.createdSuccesfully')]);
            } else {
                $dateConvert = Helper::date($request->input('date'));
                $cpt=CPTCode::where('udid',$request->cptCode)->first();
                $timeLog = array();
                if (!empty($request->category)) {
                    $timeLog['categoryId'] = $request->category;
                }
                if (!empty($request->loggedBy)) {
                    $loggedBy = Helper::entity('staff', $request->input('loggedBy'));
                    $timeLog['loggedId'] = $loggedBy;
                }
                if (!empty($request->performedBy)) {

                    $performedBy = Helper::entity('staff', $request->input('performedBy'));
                    $timeLog['performedId'] = $performedBy;
                }
                if (!empty($request->date)) {
                    $timeLog['date'] = $dateConvert;
                }
                if (!empty($request->timeAmount)) {
                    $timeLog['timeAmount'] = $request->input('timeAmount');
                }
                if (!empty($request->cptCode)) {
                    $timeLog['cptCodeId'] = $cpt;
                }
                $timeLog['updatedBy'] = Auth::id();
                $data = PatientTimeLog::where('udid', $timelogId)->update($timeLog);
                if ($request->input('noteId')) {
                    $noteData = ['note' => $request->input('note'), 'updatedBy' => Auth::id()];
                    Note::where('id', $request->input('noteId'))->update($noteData);
                } else {
                    $time = PatientTimeLog::where('udid', $timelogId)->first();
                    $noteData = [
                        'note' => $request->input('note'), 'entityType' => $request->input('entityType'), 'referenceId' => $time->id,
                        'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id(),
                    ];
                    $note = Note::create($noteData);
                }
                $data = response()->json(['message' => trans('messages.updatedSuccesfully')]);
            }
            DB::commit();
            return $data;
        // } catch (Exception $e) {
        //     DB::rollback();
        //     return response()->json(['message' => $e->getMessage()],  500);
        // }
    }

    // List Patient TimeLog
    public function patientTimeLogList($request, $entity, $id, $timelogId)
    {
        try {
            if (!$timelogId) {
                $patient = Helper::entity($entity, $id);
                $getPatient = PatientTimeLog::where('patientId', $patient)->with('category', 'logged', 'performed', 'notes')->latest()->get();
                return fractal()->collection($getPatient)->transformWith(new PatientTimeLogTransformer())->toArray();
            } else {
                $getPatient = PatientTimeLog::where('udid', $timelogId)->with('category', 'logged', 'performed', 'notes')->first();
                return fractal()->item($getPatient)->transformWith(new PatientTimeLogTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Patient TimeLog
    public function patientTimeLogDelete($request, $entity, $id, $timelogId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            PatientTimeLog::where('udid', $timelogId)->update($data);
            PatientTimeLog::where('udid', $timelogId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
