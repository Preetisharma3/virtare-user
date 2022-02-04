<?php

namespace App;

use Carbon\Carbon;


class Helper
{
  
   public static function email($email)
    {
        return $email;
    }
   

    public static function dateGroup($data, $date_field)
    {
        $res =  $data->groupBy(function ($result, $key) use ($date_field) {
            $dt = Carbon::parse($result->{$date_field});
            return $result->{$date_field};
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
}