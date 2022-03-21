<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ProgramService;

class ProgramController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function listProgram(Request $request, $id = null)
  {
    return (new ProgramService)->programList($request, $id);
  }

  public function createProgram(Request $request)
  {
    return (new ProgramService)->createProgram($request);
  }

  public function updateProgram(Request $request, $id)
  {
    return (new ProgramService)->updateProgram($request, $id);
  }

  // public function editProgram(Request $request,$id)
  // {
  //   return (new ProgramService)->editProgram($request,$id);
  // }

  public function deleteProgram(Request $request, $id)
  {
    return (new ProgramService)->deleteProgram($request, $id);
  }
}
