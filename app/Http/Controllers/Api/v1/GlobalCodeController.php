<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\Api\GlobalCodeService;
use App\Http\Requests\GlobalCode\GlobalCodeRequest;
use App\Http\Requests\GlobalCode\GlobalCodeUpdateRequest;
use Laravel\Lumen\Routing\Controller as BaseController;

class GlobalCodeController extends BaseController
{
    public function globalCodeCategory(Request $request, $id = null)
    {
        return (new GlobalCodeService)->globalCodeCategoryList($request, $id);
    }

    public function globalCode(Request $request, $id = null)
    {
        return (new GlobalCodeService)->globalCodeList($request, $id);
    }

    public function createGlobalCode(GlobalCodeRequest $request)
    {
        return (new GlobalCodeService)->globalCodeCreate($request);
    }

    public function updateGlobalCode(GlobalCodeUpdateRequest $request, $id)
    {
        return (new GlobalCodeService)->globalCodeUpdate($request, $id);
    }

    public function deleteGlobalCode(Request $request, $id)
    {
        return (new GlobalCodeService)->globalCodeDelete($request, $id);
    }

    public function globalStartEndDate(Request $request, $id)
    {
        return (new GlobalCodeService)->getGlobalStartEndDate($request, $id);
    }
}
