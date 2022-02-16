<?php

namespace App\Services\Api;

use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient\PatientTimeLog;
use App\Transformers\Patient\PatientTimeLogTransformer;

class TimeLogService
{
    public function timeLogList($request, $id)
    {
        if (!$id) {
            $data = PatientTimeLog::with('category', 'logged', 'performed')->get();
            return fractal()->collection($data)->transformWith(new PatientTimeLogTransformer())->toArray();
        } else {
            $data = PatientTimeLog::where('udid', $id)->with('category', 'logged', 'performed')->first();
            return fractal()->item($data)->transformWith(new PatientTimeLogTransformer())->toArray();
        }
    }

    public function timeLogUpdate($request, $id)
    {
        $time = Helper::time($request->input('timeAmount'));
        $noteData=['note'=>$request->input('note')];
        $note=Note::where('id',$request->input('noteId'))->update($noteData);
        $input = ['performedId' => $request->input('performed'), 'patientId' => $request->input('patient'), 'timeAmount' => $time,'updatedBy'=>Auth::id()];
        PatientTimeLog::where('udid', $id)->update($input);
        $data = PatientTimeLog::where('udid', $id)->with('category', 'logged', 'performed')->first();
        return fractal()->item($data)->transformWith(new PatientTimeLogTransformer())->toArray();
    }

    public function timeLogDelete($request, $id)
    {
        $input = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
        $data=PatientTimeLog::where('udid', $id)->update($input);
        PatientTimeLog::where('udid', $id)->delete();
        return response()->json(['message'=> 'deleted successfully']);
    }
}
