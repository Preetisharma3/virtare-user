<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\FileService;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function createFile(request $request)
    {
        return (new FileService)->fileCreate( $request);
    }

    public function deleteDocument(request $request)
    {
        return (new FileService)->fileDelete($request);
    }


}
