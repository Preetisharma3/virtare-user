<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\GlobalCodeService;
use App\Http\Requests\GlobalCode\GlobalCodeRequest;
use Laravel\Lumen\Routing\Controller as BaseController;

class GlobalCodeController extends BaseController
{
    public function globalCodeCategory(Request $request)
    {
        return (new GlobalCodeService)->globalCodeCategoryList($request);
    }

    public function createGlobalCode(GlobalCodeRequest $request)
    {
        return (new GlobalCodeService)->globalCodeCreate($request);
    }

    public function updateGlobalCode(Request $request, $id)
    {
        return (new GlobalCodeService)->globalCodeUpdate($request, $id);
    }

    public function deleteGlobalCode(Request $request, $id)
    {
        return (new GlobalCodeService)->globalCodeDelete($request, $id);
    }
}
