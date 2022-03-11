<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
// use App\Library\PhpExcelExport;
use App\Models\Task\Task;
use App\Models\CPTCode\CPTCode;
use App\Models\Template\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientTimeLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Transformers\Task\TaskCategoryTransformer;
use App\Models\GeneralParameter\GeneralParameterGroup;
use App\Transformers\GeneralParameter\GeneralParameterTransformer;

class ExcelGeneratorService
{
    public static function excelTimeLogExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $post = $request->all();
        if(isset($post["fromDate"]) && !empty($post["fromDate"])){
            $fromDate = date('Y-m-d', $request->get("fromDate"));
        }
        else
        {
            $fromDate = "";
        }

        if($request->get("toDate")){
            $toDate = date('Y-m-d', $request->get("toDate"));
        }
        else
        {
            $toDate = "";
        }

        if(!empty($fromDate) && !empty($toDate))
        {
            $responseData = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->whereBetween('date', [$fromDate, $toDate])->get();
        }
        else
        {
            $responseData = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->get();
        }
 
        $headingFrom = "A1"; // or any value
        $headingTo = "E1"; // or any value
        $sheet->setCellValue('A1', 'Patient TimeLog Report')->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:E2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->getColumnDimension('E')->setWidth(120, 'pt');
        $sheet->setCellValue('A2', 'Staff Name')
                ->setCellValue('B2', 'Patient Name')
                ->setCellValue('C2', 'Cpt Code')
                ->setCellValue('D2', 'Time')
                ->setCellValue('E2', 'Notes');
        $k = 3;
        if(!empty($responseData)){
            $dataObj = $responseData;
            for ($i = 0; $i < count($dataObj); $i++) {
                $staff_name = @$dataObj[$i]->performed->firstName.' '.@$dataObj[$i]->performed->lastName;
                $patient_name = @$dataObj[$i]->patient->firstName.' '.@$dataObj[$i]->patient->middleName.' '.@$dataObj[$i]->patient->lastName;
                $cpt_code = (!empty($dataObj[$i]->cptCode->name))?$dataObj[$i]->cptCode->name:'';
                $time = $dataObj[$i]->timeAmount/60;
                $notes = (!empty($dataObj[$i]->notes->note))?$dataObj[$i]->notes->note:'';

                $sheet->setCellValue('A' . $k, $staff_name);
                $sheet->setCellValue('B' . $k, $patient_name);
                $sheet->setCellValue('C' . $k, $cpt_code);
                $sheet->setCellValue('D' . $k, $time);
                $sheet->setCellValue('E' . $k, $notes);
                $k++;
            }
        }
        $fileName = "timeLogReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }


    public static function taskReportExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $writer = new Xlsx($spreadsheet);
        $post = $request->all();
        if(isset($post["fromDate"]) && !empty($post["fromDate"])){
            $fromDate = date('Y-m-d', $request->get("fromDate"));
        }
        else
        {
            $fromDate = "";
        }

        if($request->get("toDate")){
            $toDate = date('Y-m-d', $request->get("toDate"));
        }
        else
        {
            $toDate = "";
        }

        if(!empty($fromDate) && !empty($toDate))
        {
            $resultData = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->whereBetween('dueDate', [$fromDate, $toDate])->latest()->get();
        }
        else
        {
            $resultData = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->get();
        }
        
        $headingFrom = "A1"; // or any value
        $headingTo = "F1"; // or any value
        $sheet->setCellValue('A1', 'Task Report')->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:F2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->getColumnDimension('E')->setWidth(80, 'pt');
        $sheet->getColumnDimension('F')->setWidth(80, 'pt');
        $sheet->setCellValue('A2', 'Task Name')
                ->setCellValue('B2', 'Task Status')
                ->setCellValue('C2', 'Priority')
                ->setCellValue('D2', 'Category')
                ->setCellValue('E2', 'Due Date')
                ->setCellValue('F2', 'Assigned By');
        $k = 3;
        if(!empty($resultData))
        {
            $cat_list = "";
            for ($i = 0; $i < count($resultData); $i++) {
                if(!empty($resultData[$i]->taskCategory))
                {
                    $taskCategory = fractal()->collection($resultData[$i]->taskCategory)->transformWith(new TaskCategoryTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
                    if(!empty($taskCategory))
                    {
                        $cat_list = "";
                        foreach($taskCategory as $cat)
                        {
                            $cat_list .= $cat["taskCategory"].",";
                        }
                        $cat_string = substr($cat_list, 0, -2);
                    }  
                }
                else
                {
                    $cat_string = "";
                }

                $dueDate=date('D d, Y',strtotime($resultData[$i]->dueDate));

                $sheet->setCellValue('A' . $k, $resultData[$i]->title);
                $sheet->setCellValue('B' . $k, $resultData[$i]->taskStatus->name);
                $sheet->setCellValue('C' . $k, $resultData[$i]->priority->name);
                $sheet->setCellValue('D' . $k, $cat_string);
                $sheet->setCellValue('E' . $k, $dueDate);
                $sheet->setCellValue('F' . $k, $resultData[$i]->user->email);
                $k++;
            }
        }
        $fileName = "TaskReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }

    public static function excelCptCodeExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = CPTCode::with('provider', 'service', 'duration')->orderBy('createdAt', 'DESC')->get();
       
        $headingFrom = "A1"; // or any value
        $headingTo = "D1"; // or any value
        $sheet->setCellValue('A1', 'Cpt Code Report')->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:D2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->setCellValue('A2', 'Cpt Code')
                ->setCellValue('B2', 'Description')
                ->setCellValue('C2', 'Billing Amout')
                ->setCellValue('D2', 'Active/Inactive');
        $k = 3;
        if(!empty($resultData)){
            for ($i = 0; $i < count($resultData); $i++) {
                $status = $resultData[$i]->isActive ? "True" : "False";
                $sheet->setCellValue('A' . $k, $resultData[$i]->name);
                $sheet->setCellValue('B' . $k, $resultData[$i]->description);
                $sheet->setCellValue('C' . $k, $resultData[$i]->billingAmout);
                $sheet->setCellValue('D' . $k, $status);
                $k++;
            }
        }
        
        $fileName = "cptCodeReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }

    public static function generalParameterExcelExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = GeneralParameterGroup::with('generalParameter')->orderBy('createdAt', 'DESC')->get();
        $headingFrom = "A1"; // or any value
        $headingTo = "E1"; // or any value
        $sheet->setCellValue('A1', 'General Parameter Report')->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:E2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->getColumnDimension('E')->setWidth(120, 'pt');
        $sheet->setCellValue('A2', 'General Parameters Group')
                ->setCellValue('B2', 'Device Type')
                ->setCellValue('C2', 'Type')
                ->setCellValue('D2', 'High Limit')
                ->setCellValue('E2', 'Low Limit');
        $k = 3;
        if(!empty($resultData)){
            $generalParameter = array();
            for ($i = 0; $i < count($resultData); $i++) {
                $generalParameter = fractal()->collection($resultData[$i]->generalParameter)->transformWith(new GeneralParameterTransformer())->toArray();
                if(count($generalParameter["data"]) > 0){
                    for($j = 0; $j < count($generalParameter["data"]); $j++)
                    {
                        $deviceType =   (!empty($resultData[$i]->deviceType->name))?$resultData[$i]->deviceType->name:'';
                        $type   =  isset($generalParameter["data"][$j]["vitalFieldName"])?$generalParameter["data"][$j]["vitalFieldName"]:'';
                        $highLimit  = isset($generalParameter["data"][$j]["highLimit"])?$generalParameter["data"][$j]["highLimit"]:'';
                        $lowLimit   =  isset($generalParameter["data"][$j]["lowLimit"])?$generalParameter["data"][$j]["lowLimit"]:'';

                        $sheet->setCellValue('A' . $k, $resultData[$i]->name);
                        $sheet->setCellValue('B' . $k, $deviceType);
                        $sheet->setCellValue('C' . $k, $type);
                        $sheet->setCellValue('D' . $k, $highLimit);
                        $sheet->setCellValue('E' . $k, $lowLimit);
                        $k++;
                    }
                }
                else
                {
                    $deviceType =  (!empty($resultData[$i]->deviceType->name))?$resultData[$i]->deviceType->name:'';
                    $type       =   "";
                    $highLimit  =   "";
                    $lowLimit   =   "";
                    $sheet->setCellValue('A' . $k, $resultData[$i]->name);
                    $sheet->setCellValue('B' . $k, $deviceType);
                    $sheet->setCellValue('C' . $k, $type);
                    $sheet->setCellValue('D' . $k, $highLimit);
                    $sheet->setCellValue('E' . $k, $lowLimit);
                    $k++;
                }
                
            }
        }
        
        $fileName = "generalParameterReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }

    public static function templateExcelExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = Template::all();
        
        $headingFrom = "A1"; // or any value
        $headingTo = "B1"; // or any value
        $sheet->setCellValue('A1', 'Template Report')->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setSize(15);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:B2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->setCellValue('A2', 'Template')
                ->setCellValue('B2', 'Status');
                
        $k = 3;
        if(!empty($resultData)){
            for ($i = 0; $i < count($resultData); $i++) {
                $status = $resultData[$i]->isActive ? "true":"false";
                $sheet->setCellValue('A' . $k, $resultData[$i]->name);
                $sheet->setCellValue('B' . $k, $status);
                $k++;
            }
        }
        
        $fileName = "templateReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }

    public static function inventoryExcelExport($request,$isAvailable="1",$deviceType="99")
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = DB::select('CALL inventoryList("' . $isAvailable . '","' . $deviceType . '")');
        $excelObj = array();
        
        $headingFrom = "A1"; // or any value
        $headingTo = "E1"; // or any value
        $sheet->setCellValue('A1', 'General Parameter Report')->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:E2")->getFont()->setBold( true );
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->getColumnDimension('E')->setWidth(120, 'pt');
        $sheet->setCellValue('A2', 'Device Type')
                ->setCellValue('B2', 'Model Number')
                ->setCellValue('C2', 'Serial Number')
                ->setCellValue('D2', 'Mac Address')
                ->setCellValue('E2', 'Active/Inactive');
        $k = 3;

        if(!empty($resultData)){
            for ($i = 0; $i < count($resultData); $i++) {
                $deviceType = (!empty($resultData[$i]->model->deviceType->name)) ? $resultData[$i]->model->deviceType->name : $resultData[$i]->deviceType;
                $modelNumber = $resultData[$i]->modelNumber ? $resultData[$i]->modelNumber : $resultData[$i]->model->modelName;
                $status = $resultData[$i]->isActive ? "True" : "False";
                $sheet->setCellValue('A' . $k, $deviceType);
                $sheet->setCellValue('B' . $k, $modelNumber);
                $sheet->setCellValue('C' . $k, $resultData[$i]->serialNumber);
                $sheet->setCellValue('D' . $k, $resultData[$i]->macAddress);
                $sheet->setCellValue('E' . $k, $status);
                $k++;
            }
        }
        $fileName = "inventoryReport_".time().".xlsx";
        ExcelGeneratorService:: writerSave($writer,$fileName);
        exit;
    }
    
    public static function writerSave($writer,$fileName)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
