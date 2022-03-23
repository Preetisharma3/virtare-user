<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Tag\Tag;
use Illuminate\Support\Str;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Document\DocumentTransformer;

class DocumentService
{
    // Add Document
    public function documentCreate($request, $entity, $id, $documentId)
    {
        // DB::beginTransaction();
       // try {
             if (!$documentId) {
                $reference = Helper::entity($entity, $id);
                $input = [
                    'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                    'referanceId' => $reference, 'entityType' => $entity, 'udid' => Str::uuid()->toString(), 'createdBy' => Auth::id()];
                 $data = JSON_ENCODE(
                $input,
            );
               $id =  DB::select(
                "CALL createAddDocuments('" . $data . "')"
            );
            $document=$id[0]->documentId;
                $tags = $request->input('tags');
                foreach ($tags as $value) {
                    $tag = [
                        'tag' => $value, 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString(), 'documentId' => $document
                    ];
                $data = JSON_ENCODE(
                $tag
            );
             
                    $id =  DB::select(
                "CALL createAddTags('" . $data . "')"
            );
        
                }
                 $message = ['message' => trans('messages.createdSuccesfully')];
            return $message;
                // $getDocument = Document::where([['id', $document->id], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
            
                // $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                // $message = ['message' => trans('messages.createdSuccesfully')];
              
            }    else {

            $name = $request->name;
            $filePath = $request->filePath;
            $documentTypeId = $request->documentTypeId;
            $updatedBy = Auth::id();
           




                $input = [
                    'name' => $request->input('name'), 'filePath' => $request->input('document'), 'documentTypeId' => $request->input('type'),
                     'updatedBy' => Auth::id()];
                      $data = JSON_ENCODE(
                      $input,
                 );

                $document = Document::where('udid', $documentId)->first();
                $tagData = ['deletedBy' => Auth::id(), 'isActive' => 0, 'isDelete' => 1];
                Tag::where('documentId', $document->id)->update($tagData);
                Tag::where('documentId', $document->id)->delete();
                Document::where('udid', $documentId)->update($input);
               // $document=$id[0]->documentId;

                $tags = $request->input('tags');
                foreach ($tags as $value) {
                     $tag = [
                        'tag' => $value, 'createdBy' => Auth::id(), 'udid' => Str::uuid()->toString(), 'documentId' => $document
                    ];
                $data = JSON_ENCODE(
                $tag
                     );
                    
                $documentData =  DB::select(
                "CALL UpdateDocuments('" . $id . "','" . $name . "','" . $filePath . "','" . $documentTypeId . "','" . $updatedBy . "')"
            );
            //         Tag::create($tag);
            //     
                  // $getDocument = Document::where([['udid', $documentId], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                  // $userdata = fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
                
        }
         $message = ['message' => trans('messages.updatedSuccesfully')];
         return $message;

            }    
             
         }
        
        
       
            

    // List Document
    public function documentList($request, $entity, $id, $documentId)
    {
        try {
            $reference = Helper::entity($entity, $id);
            
            if ($documentId) {
                $getDocument = Document::where([['udid', $documentId], ['entityType', $entity]])->with('documentType', 'tag.tags')->first();
                return fractal()->item($getDocument)->transformWith(new DocumentTransformer())->toArray();
            } else {
                $getDocument = Document::where([['referanceId', $reference], ['entityType', $entity]])->with('documentType', 'tag.tags')->latest()->get();
                return fractal()->collection($getDocument)->transformWith(new DocumentTransformer())->toArray();
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
                Document::where([['udid', $documentId], ['entityType', $entity]])->update($data);
                tag::where('documentId', $documentId)->update($data);
                Document::where([['udid', $documentId], ['entityType', $entity]])->delete();
                tag::where('documentId', $documentId)->delete();
            DB::commit();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
