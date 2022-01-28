<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\DocumentService;

class DocumentController extends Controller
{
    public function createDocument(Request $request,$id)
    {
        return (new DocumentService)->documentCreate( $request,$id);
    }

    public function listDocument(Request $request,$entity,$id,$documentId=null)
    {
        return (new DocumentService)->documentList($request,$entity ,$id,$documentId);
    }


}
