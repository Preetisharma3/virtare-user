<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Note\NoteTransformer;

class NoteService
{
    public function noteAdd($request, $entity, $id)
    {
        try {
            $userId = Auth::id();
            $referenceId=Helper::entity($entity,$id);
            $dataConvert = Helper::date($request->input('date'));
            $input = [
                'date' => $dataConvert, 'categoryId' => $request->input('category'), 'type' => $request->input('type'),
                'note' => $request->input('note'), 'udid' => Str::uuid()->toString(), 'createdBy' => $userId, 'referenceId' => $referenceId, 'entityType' => $request->input('entityType')
            ];
            Note::create($input);
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function noteList($request, $entity, $id,$noteId)
    {
        try {
            if($request->latest){
            $referenceId=Helper::entity($entity,$id);
                $note = Note::where([['referenceId',$referenceId],['entityType', $entity]])->with('typeName', 'category')->latest('createdAt')->first();
                return fractal()->item($note)->transformWith(new NoteTransformer())->toArray();
            }else{
                if($noteId){
                    $note = Note::where('udid', $noteId)->with('typeName', 'category')->orderBy('createdAt','DESC')->first();
                    return fractal()->item($note)->transformWith(new NoteTransformer())->toArray();
                
                }else{
                    $note = Note::where('entityType', $entity)->with('typeName', 'category')->orderBy('createdAt','DESC')->get();
                    return fractal()->collection($note)->transformWith(new NoteTransformer())->toArray();
                
                }
               }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
