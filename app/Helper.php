<?php

namespace App;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;


class Helper
{
  
   public static function email($email)
    {
        return $email;
    }
   

    public static function dateGroup($data, $date_field)
    {
        $res =  $data->sortBy($date_field)->groupBy(function ($result, $key) use ($date_field) {
            $dt = Carbon::parse($result->{$date_field});
            return $dt->format('Y-m-d');
        });
        $patientData = array();
        foreach ($res as $key => $value) {
            $patient = array();
            $patient['year'] = $key;
            $patient['data'] = $value;
            array_push($patientData, $patient);
        }
        return $patientData;
    }

    public static function date($date)
    {
        $date = Carbon::createFromTimestamp($date)->format('Y-m-d H:i:s');

        return $date;
    }
    public static function time($date)
    {
        $date = Carbon::createFromTimestamp($date)->format('H:i:s');

        return $date;
    }

    public static function entity($entity, $id)
    {
        if ($entity == 'patient') {
            $data=Patient::where('udid', $id)->first();
        } elseif ($entity == 'staff') {
            $data=Staff::where('udid', $id)->first();
        }
        return $data->id;
    }
}