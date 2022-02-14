<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Note\Note;
use Illuminate\Support\Str;
use App\Models\Module\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Note\NoteTransformer;
use App\Transformers\Module\ModuleTransformer;

class NoteService
{
    public function noteAdd($request, $entity, $id)
    {
        try {
            $userId = Auth::id();
            $dataConvert = Helper::date($request->input('date'));
            $input = [
                'date' => $dataConvert, 'categoryId' => $request->input('category'), 'type' => $request->input('type'),
                'note' => $request->input('note'), 'udid' => Str::uuid()->toString(), 'createdBy' => $userId, 'referenceId' => $userId, 'entityType' => $request->input('entityType')
            ];
            Note::create($input);
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function noteList($request, $entity, $id)
    {
        try {
            $note = Note::where('entityType', $entity)->with('typeName','category')->get();
            return fractal()->collection($note)->transformWith(new NoteTransformer())->toArray();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}