<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\InventoryService;
use App\Services\Api\ExcelGeneratorService;
use App\Services\Api\ExportReportRequestService;

class InventoryController extends Controller
{
    public function index(request $request, $id = NULL)
    {
        if (!empty($id)) {
            return (new InventoryService)->geVentoryById($id);
        } else {
            return (new InventoryService)->index($request);
        }
    }

    public function store(Request $request)
    {
        return (new InventoryService)->store($request);
    }

    public function update(Request $request, $id)
    {
        return (new InventoryService)->update($request, $id);
    }

    public function destroy($id)
    {
        return (new InventoryService)->destroy($id);
    }

    public function getModels(Request $request)
    {
        return (new InventoryService)->getModels($request);
    }

    public function inventoryReport(Request $request, $id)
    {
        if ($id) {
            $reportType = "inventory_report";
            $checkReport = ExportReportRequestService::checkReportRequest($id, $reportType);
            if ($checkReport) {
                ExcelGeneratorService::inventoryExcelExport($request);
            } else {
                return response()->json(['message' => "User not Access to download Report."], 500);
            }
        } else {
            return response()->json(['message' => "invalid URL."], 500);
        }
    }
}
