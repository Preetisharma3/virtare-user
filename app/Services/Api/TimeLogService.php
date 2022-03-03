<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Str;
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
            $data = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->paginate(env('PER_PAGE',20));
            return fractal()->collection($data)->transformWith(new PatientTimeLogTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        } else {
            $data = PatientTimeLog::where('udid', $id)->with('category', 'logged', 'performed', 'notes')->first();
            return fractal()->item($data)->transformWith(new PatientTimeLogTransformer())->toArray();
        }
    }

    public function timeLogUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            $time = Helper::time($request->input('timeAmount'));
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
            $staffid = Helper::entity('staff',$request->input('staff'));
            $patient=Helper::entity('patient',$request->input('patient'));
            $input = ['performedId' => $staffid, 'patientId' => $patient, 'timeAmount' => $time, 'updatedBy' => Auth::id()];
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
}
