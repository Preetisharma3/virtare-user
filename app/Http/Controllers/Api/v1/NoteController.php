<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\NoteService;
use App\Services\Api\ModuleService;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function addNote(Request $request, $entity, $id=null){

        return (new NoteService)->noteAdd($request, $entity, $id);
    }

    public function listNote(Request $request, $entity, $id=null){

        return (new NoteService)->noteList($request, $entity, $id);
    }
   
}
