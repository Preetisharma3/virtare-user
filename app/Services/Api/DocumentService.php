<?php

namespace App\Services\Api;

use App\Helper;
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
    public function documentCreate($request, $entity, $id, $documentId)
    {
        DB::beginTransaction();
        try {
            if (!$documentId) {
                $referenceId = Helper::entity($entity, $id);
                $input = [
                    'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                    'referanceId' => $referenceId, 'entityType' => $request->input('entity'), 'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id()
                ];
                $document = Document::create($input);
                $tags = $request->input('tags');
                foreach ($tags as $value) {
                    $tag = [
                        'tag' => $value, 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString(), 'documentId' => $document['id']
                    ];
                    Tag::create($tag);
                }
                $getDocument = Document::where([['id', $document->id], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                $message = response()->json(['message' => trans('messages.created_succesfully')]);
            } else {
                $input = [
                    'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                    'updatedBy' => Auth::id()
                ];
                $document = Document::where('udid', $documentId)->first();
                $tagData = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
                Tag::where('documentId', $document->id)->update($tagData);
                Tag::where('documentId', $document->id)->delete();
               
                Document::where('udid', $documentId)->update($input);
                $tags = $request->input('tags');
                foreach ($tags as $value) {
                    $tag = [
                        'tag' => $value, 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString(), 'documentId' => $document->id
                    ];
                    Tag::create($tag);
                }
                $getDocument = Document::where([['udid', $documentId], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                $message = response()->json(['message' => trans('messages.updated_succesfully')]);
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
                $referenceId = Helper::entity($entity, $id);
                $getDocument = Document::where([['referanceId', $referenceId], ['entityType', $entity]])->with('documentType', 'tag.tags')->latest()->first();
                return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
            } else {
                $referenceId = Helper::entity($entity, $id);
                if ($documentId) {
                    $getDocument = Document::where([['udid', $documentId], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                    return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                } else {
                    $getDocument = Document::where([['referanceId', $referenceId], ['entityType', $entity]])->with('documentType', 'tag.tags')->orderBy('createdAt', 'DESC')->get();
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
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            if ($entity == 'patient') {
                Document::where([['udid', $documentId], ['entityType', 'patient']])->update($data);
                tag::where('documentId', $documentId)->update($data);
                Document::where([['udid', $documentId], ['entityType', 'patient']])->delete();
                tag::where('documentId', $documentId)->delete();
            }
            DB::commit();
            return response()->json(['message' => trans('messages.deleted_succesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
