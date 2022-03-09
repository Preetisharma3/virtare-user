<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ExportReportRequestService;

class ExportReportRequestController extends Controller
{
    public function addExportRequest(Request $request)
    {
        return (new ExportReportRequestService)->insertExportRequest($request);
    }

}
