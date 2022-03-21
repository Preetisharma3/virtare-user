<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Note\NoteTransformer;

class NoteService
{
    public function noteAdd($request, $entity, $id)
    {
        try {
            $userId = Auth::id();
            $referenceId = Helper::entity($entity, $id);
            $dataConvert = Helper::date($request->input('date'));
            $input = [
                'date' => $dataConvert, 'categoryId' => $request->input('category'), 'type' => $request->input('type'),
                'note' => $request->input('note'), 'udid' => Str::uuid()->toString(), 'createdBy' => $userId, 'referenceId' => $referenceId, 'entityType' => $request->input('entityType')
            ];
            Note::create($input);
            return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function noteList($request, $entity, $id, $noteId)
    {
        try {

            if ($request->latest) {
                $referenceId = Helper::entity($entity, $id);
                $note = DB::select('CALL NotesListByPatientId(' . $referenceId . ')',);
                // $note = Note::where([['referenceId', $referenceId], ['entityType', $entity]])->with('typeName', 'category')->latest('createdAt')->get();
                if (!empty($note)) {
                    return fractal()->collection($note)->transformWith(new NoteTransformer())->toArray();
                } else {
                    $note = [];
                    return $note;
                }
            } else {
                // $note = Note::where('entityType', $entity)->with('typeName', 'category')->get();
                $referenceId = Helper::entity($entity, $id);
                $note = DB::select('CALL NotesListByPatientId(' . $referenceId . ')',);
                if (!empty($note)) {
                    return fractal()->collection($note)->transformWith(new NoteTransformer())->toArray();
                } else {
                    $note = [];
                    return $note;
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function patientNoteList($request)
    {
        try {
            $note = Note::where([['referenceId', auth()->user()->patient->id], ['entityType', 'patient']])->get();
            return fractal()->collection($note)->transformWith(new NoteTransformer(false))->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
