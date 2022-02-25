<?php

namespace App;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use App\Models\User\User;
use App\Models\Patient\PatientTimeLog;

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
            $data = Patient::where('udid', $id)->first();
        } elseif ($entity == 'staff') {
            $data = Staff::where('udid', $id)->first();
        } elseif ($entity == 'auditlog') {
            $data = PatientTimeLog::where('udid', $id)->first();
        }
        return $data->id;
    }
    public static function updateFreeswitchUser(){
        $users = User::all();
        ob_start();
       
        ?><document type="freeswitch/xml">
                <section name="directory">
                    <domain name="51.81.193.156">
                        <params>
                            <param name="dial-string" value="{presence_id=${dialed_user}@${dialed_domain}}${sofia_contact(${dialed_user}@${dialed_domain})}"/>
                        </params>
                        <groups>
                            <group name="default">
                                <users>
                                    <?php
                                        foreach($users as $user) {
                                            if($user->staff){
                                                $name = $user->staff->firstName." ".$user->staff->lastName;
                                            }elseif($user->patient){
                                                $name = $user->patient->firstName." ".$user->patient->lastName;
                                            }else{
                                                $name = '';
                                            }
                                    ?>
                                    <user id="UR<?php echo $user->id; ?>">
                                        <params>
                                            <param name="password" value="123456"/>
                                        </params>
                                        <variables>
                                            <variable name="accountcode" value="UR<?php echo $user->id; ?>"/>
                                            <variable name="user_context" value="default"/>
                                            <variable name="effective_caller_id_name" value="<?php echo $name; ?>"/>
                                            <variable name="effective_caller_id_number" value="UR<?php echo $user->id; ?>"/>
                                            <variable name="outbound_caller_id_name" value="<?php echo $name; ?>"/>
                                            <variable name="outbound_caller_id_number" value="UR<?php echo $user->id; ?>"/>
                                        </variables>
                                    </user>
                                    <?php } ?>
                                </users>
                            </group>
                        </groups>
                    </domain>
                </section>
            </document>
        <?php
        $contents = ob_get_contents();
        ob_end_clean();
        $directory = fopen(base_path()."/public/directory.xml", "w") or die("Unable to open file!");
        fwrite($directory, $contents);
        fclose($directory);
    }
}
