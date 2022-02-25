<?php

namespace App;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use App\Models\User\User;

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
    public static function updateFreeswitchConfrence(){
        ob_start();
       
        ?><document type="freeswitch/xml">
            <section name="dialplan" description="RE Dial Plan For FreeSwitch">
                <context name="default">





    
                    <extension name="Local_Extension">
                        <condition field="destination_number" expression="^(UR\d{1,20})$">
                            <action application="export" data="dialed_extension=$1"/>
                            <!-- bind_meta_app can have these args <key> [a|b|ab] [a|b|o|s] <app> -->
                            <action application="bind_meta_app" data="1 b s execute_extension::dx XML features"/>
                            <action application="bind_meta_app" data="2 b s record_session::$${recordings_dir}/${caller_id_number}.${strftime(%Y-%m-%d-%H-%M-%S)}.wav"/>
                            <action application="bind_meta_app" data="3 b s execute_extension::cf XML features"/>
                            <action application="bind_meta_app" data="4 b s execute_extension::att_xfer XML features"/>
                            <action application="set" data="ringback=${us-ring}"/>
                            <action application="set" data="transfer_ringback=$${hold_music}"/>
                            <action application="set" data="call_timeout=30"/>
                            <!-- <action application="set" data="sip_exclude_contact=${network_addr}"/> -->
                            <action application="set" data="hangup_after_bridge=true"/>
                            <!--<action application="set" data="continue_on_fail=NORMAL_TEMPORARY_FAILURE,USER_BUSY,NO_ANSWER,TIMEOUT,NO_ROUTE_DESTINATION"/> -->
                            <action application="set" data="continue_on_fail=true"/>
                            <action application="hash" data="insert/${domain_name}-call_return/${dialed_extension}/${caller_id_number}"/>
                            <action application="hash" data="insert/${domain_name}-last_dial_ext/${dialed_extension}/${uuid}"/>
                            <action application="set" data="called_party_callgroup=${user_data(${dialed_extension}@${domain_name} var callgroup)}"/>
                            <action application="hash" data="insert/${domain_name}-last_dial_ext/${called_party_callgroup}/${uuid}"/>
                            <action application="hash" data="insert/${domain_name}-last_dial_ext/global/${uuid}"/>
                            <!--<action application="export" data="nolocal:rtp_secure_media=${user_data(${dialed_extension}@${domain_name} var rtp_secure_media)}"/>-->
                            <action application="hash" data="insert/${domain_name}-last_dial/${called_party_callgroup}/${uuid}"/>
                            <action application="bridge" data="user/${dialed_extension}@${domain_name}"/>
                            <action application="answer"/>
                            <action application="sleep" data="1000"/>
                            <action application="bridge" data="loopback/app=voicemail:default ${domain_name} ${dialed_extension}"/>
                        </condition>
                    </extension>
                </context>
            </section>
        </document>
<?php
        $contents = ob_get_contents();
        ob_end_clean();
        $directory = fopen(base_path()."/public/dialplan.xml", "w") or die("Unable to open file!");
        fwrite($directory, $contents);
        fclose($directory);
    }
}