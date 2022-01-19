<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Transformers\File\FileTransformer;

class FileService
{
    public function fileCreate($request)
    {
        DB::beginTransaction();
        try {
            $file = Storage::disk('local')->put('public' . "/" . date("Y") . "/" . date("m"), $request->file);
            $data = [
                "URL" => URL::asset('storage/app/' . $file),
                "path" => "storage/app/" . $file,
            ];
            DB::commit();
            return fractal()->item($data)->transformWith(new FileTransformer)->toArray();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function fileDelete($request)
    {
        try {
            if ($request->url) {
                $url = str_replace(URL::to('/') . '/', '', $request->url);
            } else {
                $url = $request->path;
            }
            unlink(storage_path() . '/' . $url);
            return response()->json(['message' => 'file_delete'],  200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}
