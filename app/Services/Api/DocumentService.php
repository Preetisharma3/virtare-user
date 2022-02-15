<?php

namespace App\Services\Api;

use Exception;
use App\Models\Tag\Tag;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Patient\Patient;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Document\DocumentTransformer;

class DocumentService
{
    // Add Document
    public function documentCreate($request, $entity, $id, $documentId, $tagId)
    {
        DB::beginTransaction();
        try {
            if (!$documentId) {
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
                if ($entity == 'patient') {
                    $getDocument = Document::where([['id', $document->id], ['entityType', 'patient']])->with('documentType', 'tag.tags')->first();
                } elseif ($entity == 'staff') {
                    $getDocument = Document::where([['id', $document->id], ['entityType', 'staff']])->with('documentType', 'tag.tags')->first();
                }

                $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                $message = ['message' => 'created successfully'];
            } else {
                $input = [
                    'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                    'updatedBy' => 1
                ];
                $document = Document::where('id', $documentId)->update($input);
                $tags = $request->input('tags');
                $tag = ['tag' => $tags, 'updatedBy' => 1,];
                $tagData = Tag::where('id', $tagId)->update($tag);

                if ($entity == 'patient') {
                    $getDocument = Document::where([['id', $documentId], ['entityType', 'patient']])->with('documentType', 'tag.tags')->first();
                } elseif ($entity == 'staff') {
                    $getDocument = Document::where([['id', $documentId], ['entityType', 'staff']])->with('documentType', 'tag.tags')->first();
                }
                $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                $message = ['message' => 'updated successfully'];
            }
            DB::commit();
            $endData = array_merge($message, $userdata);
            return $endData;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // List Document
    public function documentList($request, $entity, $id, $documentId)
    {
        try {
            if ($request->latest) {
                if ($id) {
                    $patientId = Patient::where('udid', $id)->first();
                    $getDocument = Document::where([['referanceId', $patientId], ['entityType', $entity]])->with('documentType', 'tag.tags')->latest()->first();
                } else {
                    $user = User::where('id', Auth::id())->first();
                    $patientId = Patient::where('userId', $user->id)->first();
                    $getDocument = Document::where([['referanceId', $patientId], ['entityType', $entity]])->with('documentType', 'tag.tags')->latest()->first();
                }

                return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
            } else {
                if ($documentId) {
                    $getDocument = Document::where([['id', $documentId], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                    return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                } else {
                    $getDocument = Document::where([['referanceId', $id], ['entityType', $entity]])->with('documentType', 'tag.tags')->get();
                    return fractal()->collection($getDocument)->transformWith(new DocumentTransformer())->toArray();
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    // Delete Document
    public function documentDelete($request, $entity, $id, $documentId)
    {
        DB::beginTransaction();
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            if ($entity == 'patient') {
                Document::where([['id', $documentId], ['entityType', 'patient']])->update($data);
                tag::where('documentId', $documentId)->update($data);
                Document::where([['id', $documentId], ['entityType', 'patient']])->delete();
                tag::where('documentId', $documentId)->delete();
            }
            DB::commit();
            return response()->json(['message' => 'delete successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
