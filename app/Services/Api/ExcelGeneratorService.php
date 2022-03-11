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
            $patientData = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->whereBetween('date', [$fromDate, $toDate])->get();
        }
        else
        {
            $patientData = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->get();
        }

        $excelObj = array();
        if(!empty($patientData)){
            foreach($patientData as $data){
                $excelObj[] = array(
                    "staff_name" => @$data->performed->firstName.' '.@$data->performed->lastName,
                    "patient_name" => @$data->patient->firstName.' '.@$data->patient->middleName.' '.@$data->patient->lastName,
                    "cpt_code" =>(!empty($data->cptCode->name))?$data->cptCode->name:'',
                    "time" =>$data->timeAmount,
                    "notes"=> (!empty($data->notes->note))?$data->notes->note:''
                );
            }
        }

        $excelData = array($excelObj);
        
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["staff_name"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["patient_name"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["cpt_code"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["time"]);
            $sheet->setCellValue('E' . $k, $excelObj[$i]["notes"]);
            $k++;
        }
        
        $fileName = "timeLogReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
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
            $result = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->whereBetween('dueDate', [$fromDate, $toDate])->latest()->get();
        }
        else
        {
            $result = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->get();
        }

        $excelObj = array();
        $cat_list = "";
        if(!empty($result))
        {
            foreach($result as $data)
            {
                if(!empty($data->taskCategory))
                {
                    $taskCategory = fractal()->collection($data->taskCategory)->transformWith(new TaskCategoryTransformer)->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
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

                $excelObj[] = array(
                    'title'=>$data->title,
                    'taskStatus'=>$data->taskStatus->name,
                    'priority'=>$data->priority->name,
                    'category'=> $cat_string,
                    'dueDate'=>date('D d, Y',strtotime($data->dueDate)),
                    'assignedBy'=>$data->user->email
                );
            }
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["title"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["taskStatus"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["priority"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["category"]);
            $sheet->setCellValue('E' . $k, $excelObj[$i]["dueDate"]);
            $sheet->setCellValue('F' . $k, $excelObj[$i]["assignedBy"]);
            $k++;
        }
        
        $fileName = "TaskReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public static function excelCptCodeExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = CPTCode::with('provider', 'service', 'duration')->orderBy('createdAt', 'DESC')->get();
       
        $excelObj = array();
        if(!empty($resultData)){
            foreach($resultData as $data){
                $excelObj[] = array(
                    "cpt_code" =>$data->name,
                    'description' => $data->description,
                    'billingAmout'=>$data->billingAmout,
                    'status'=> $data->isActive ? "True" : "False"
                );
            }
        }
        
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["cpt_code"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["description"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["billingAmout"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["status"]);
            $k++;
        }
        
        // $writer->save('hello world.xlsx');
        $fileName = "cptCodeReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public static function generalParameterExcelExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = GeneralParameterGroup::with('generalParameter')->orderBy('createdAt', 'DESC')->get();

        $excelObj = array();
        if(!empty($resultData)){
            foreach($resultData as $data){
                $generalParameter = fractal()->collection($data->generalParameter)->transformWith(new GeneralParameterTransformer())->toArray();
                if(count($generalParameter["data"]) > 0){
                    for($l = 0; $l < count($generalParameter["data"]); $l++)
                    {
                        $excelObj[] = array(
                            'groupName'=>$data->name,
                            'deviceType'=>(!empty($data->deviceType->name))?$data->deviceType->name:'',
                            'type'=>isset($generalParameter["data"][$l]["vitalFieldName"])?$generalParameter["data"][$l]["vitalFieldName"]:'',
                            'highLimit'=>isset($generalParameter["data"][$l]["highLimit"])?$generalParameter["data"][$l]["highLimit"]:'',
                            'lowLimit'=>isset($generalParameter["data"][$l]["lowLimit"])?$generalParameter["data"][$l]["lowLimit"]:''
                        );
                    }
                }
                else
                {
                    $excelObj[] = array(
                        'groupName'=>$data->name,
                        'deviceType'=>(!empty($data->deviceType->name))?$data->deviceType->name:'',
                        'type'=>'',
                        'highLimit'=>'',
                        'lowLimit'=>''
                    );
                }
            }
        }
        
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["groupName"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["deviceType"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["type"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["highLimit"]);
            $sheet->setCellValue('E' . $k, $excelObj[$i]["lowLimit"]);
            $k++;
        }
        
        $fileName = "generalParameterReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public static function templateExcelExport($request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = Template::all();

        $excelObj = array();
        if(!empty($resultData)){
            foreach($resultData as $data){
                $excelObj[] = array(
                    'name' => $data->name,
                    'status' =>$data->isActive ? "true":"false"
                );
            }
        }
        
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["name"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["status"]);
            $k++;
        }
        
        $fileName = "templateReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public static function inventoryExcelExport($request,$isAvailable="1",$deviceType="99")
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $resultData = DB::select('CALL inventoryList("' . $isAvailable . '","' . $deviceType . '")');
        $excelObj = array();
        if(!empty($resultData)){
            foreach($resultData as $data){
                $excelObj[] = array(
                    'deviceType' => (!empty($data->model->deviceType->name)) ? $data->model->deviceType->name : $data->deviceType,
                    'modelNumber' => $data->modelNumber ? $data->modelNumber : $data->model->modelName,
                    'serialNumber' => $data->serialNumber,
                    'macAddress' => $data->macAddress,
                    'status' => $data->isActive ? "True" : "False"
                );
            }
        }
        
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
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["deviceType"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["modelNumber"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["serialNumber"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["macAddress"]);
            $sheet->setCellValue('E' . $k, $excelObj[$i]["status"]);
            $k++;
        }
        
        $fileName = "inventoryReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
