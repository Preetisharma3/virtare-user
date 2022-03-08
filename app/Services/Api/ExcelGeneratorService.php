<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
// use App\Library\PhpExcelExport;
use App\Models\CPTCode\CPTCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient\PatientTimeLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGeneratorService
{
    public function excelTimeLogExport()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);
        $patientData = PatientTimeLog::with('category', 'logged', 'performed', 'notes')->get();
        $excelObj = array();
        if(!empty($patientData)){
            foreach($patientData as $data){
                $excelObj[] = array(
                    "staff_name" => @$data->performed->firstName.' '.@$data->performed->lastName,
                    "patient_name" => @$data->patient->firstName.' '.@$data->patient->middleName.' '.@$data->patient->lastName,
                    "time" =>$data->timeAmount,
                    "notes"=> (!empty($data->notes->note))?$data->notes->note:''
                );
            }
        }

        $excelData = array($excelObj);
        
        $headingFrom = "A1"; // or any value
        $headingTo = "D1"; // or any value
        $sheet->setCellValue('A1', 'Patient TimeLog Report')->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle("$headingFrom:$headingTo")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("$headingFrom:$headingTo")->getFont()->setBold( true );
        $sheet->getStyle("A2:D2")->getFont()->setBold( true );
        // $sheet->getColumnDimension("A1")->setWidth(20);
        $sheet->getColumnDimension('A')->setWidth(80, 'pt');
        $sheet->getColumnDimension('B')->setWidth(80, 'pt');
        $sheet->getColumnDimension('C')->setWidth(80, 'pt');
        $sheet->getColumnDimension('D')->setWidth(120, 'pt');
        $sheet->setCellValue('A2', 'Staff Name')
                ->setCellValue('B2', 'Patient Name')
                ->setCellValue('C2', 'Time')
                ->setCellValue('D2', 'Notes');
        $k = 3;
        for ($i = 0; $i < count($excelObj); $i++) {
            $sheet->setCellValue('A' . $k, $excelObj[$i]["staff_name"]);
            $sheet->setCellValue('B' . $k, $excelObj[$i]["patient_name"]);
            $sheet->setCellValue('C' . $k, $excelObj[$i]["time"]);
            $sheet->setCellValue('D' . $k, $excelObj[$i]["notes"]);
            $k++;
        }
        
        // $writer->save('hello world.xlsx');
        $fileName = "timeLogReport_".time().".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function excelCptCodeExport()
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
        // $sheet->getColumnDimension("A1")->setWidth(20);
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
}
