<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use App\Transformers\Document\DocumentTransformer;

class DocumentService
{
    public function documentCreate($request)
    {
        DB::beginTransaction();
        try {
            $udid = Str::uuid()->toString();
            $input=['name'=>$request->input('name'),'filePath'=>$request->input('document'),
            'documentTypeId'=>$request->input('type'),'referanceId'=>1,'entityType'=>$request->entity,'udid'=>$udid];
                $document = Document::create($input);
            DB::commit();
            $getDocument = Document::where('id', $document->id)->with('documentType')->first();
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
                $getPatient = Document::where('id', $id)->with('patient', 'insuranceName', 'insuranceType')->first();
                return fractal()->item($getPatient)->transformWith(new DocumentTransformer())->toArray();
            } else {
                $getPatient = Document::with('patient', 'insuranceName', 'insuranceType')->get();
                return fractal()->collection($getPatient)->transformWith(new DocumentTransformer())->toArray();
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}