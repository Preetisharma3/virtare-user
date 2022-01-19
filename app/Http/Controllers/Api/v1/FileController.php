<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\FileService;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function createFile(Request $request)
    {
        return (new FileService)->fileCreate( $request);
    }

    public function deleteFile(Request $request)
    {
        return (new FileService)->fileDelete($request);
    }


}
