<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\BitrixService;
use App\Http\Controllers\Controller;

class BitrixFieldController extends Controller
{
  public function createBitrixField(Request $request)
  {
    return (new BitrixService)->bitrixFieldCreate($request);
  }

  public function updateBitrixField(Request $request, $id)
  {
    return (new BitrixService)->bitrixFieldUpdate($request, $id);
  }

  public function listBitrixField(Request $request, $id = null)
  {
    return (new BitrixService)->bitrixFiledGet($request, $id);
  }

  public function deleteBitrixField(Request $request, $id)
  {
    return (new BitrixService)->bitrixFieldDelete($request, $id);
  }
}
