<?php

namespace App\Services\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment\Appointment;
use App\Models\Patient\Patient;
use App\Transformers\Dummy\DummyTransformer;

class NotificationService
{
    public function notif(){
        $data = DB::select(
            'CALL notificationList()',
       );
       foreach($data as $new) {
           $patient = Patient::where('id',$new->patientId)->get();
         foreach($patient as $patientId){
             $patientsId = $patientId->id;
             
         }
       }
       
        // return  fractal()->collection($data)->transformWith(new DummyTransformer())->toArray();
    } 
}
