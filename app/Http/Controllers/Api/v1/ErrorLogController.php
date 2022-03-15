<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Library\ErrorLogGenerator;
use App\Http\Controllers\Controller;

class ErrorLogController extends Controller
{
  public function listErrorLog(Request $request, $id = null)
  {
    return (new ErrorLogGenerator)->getErrorLog($id);
  }
}
