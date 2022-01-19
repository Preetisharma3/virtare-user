<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\DB;

class FileService
{
    public function fileCreate($request)
    {
        DB::beginTransaction();
        try {
           
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function fileDelete($request)
    {
        try {
            
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }
}