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
            return $dt->format('M d, Y');
        });
        $vitalData = array();
        foreach ($res as $key => $value) {
            $vital = array();
            $vital['date'] = $key;
            $vital['value'] = $value;
            array_push($vitalData, $vital);
        }
        return $vitalData;
    }
}