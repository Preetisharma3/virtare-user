<?php

namespace App\Services\Api;

use Exception;
use App\Models\Tag\Tag;
use Illuminate\Support\Str;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use App\Transformers\Document\DocumentTransformer;

class DocumentService
{
    public function documentCreate($request, $id)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input = [
                'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                'referanceId' => $id, 'entityType' => $request->input('entity'), 'udid' => $udid, 'createdBy' => 1
            ];
            $document = Document::create($input);
            $tags = $request->input('tags');
            foreach ($tags as $value) {
                $tag = [
                    'tag' => $value, 'createdBy' => 1, 'udid' => $udid, 'documentId' => $document['id']
                ];
                Tag::create($tag);
            }
            DB::commit();
            $getDocument = Document::where('id', $document->id)->with('documentType', 'tag')->first();
            $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
            $message = ['message' => 'created successfully'];
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function documentList($request, $id)
    {
        try {
            if ($id) {
                $getDocument = Document::where('id', $id)->with('documentType', 'tag')->first();
                return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
            } else {
                $getDocument = Document::with('documentType', 'tag')->get();
                return fractal()->collection($getDocument)->transformWith(new DocumentTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
