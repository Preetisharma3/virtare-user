<?php

namespace App\Http\Controllers\Freeswitch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\User;

class DirectoryController extends Controller
{
    public function directory(Request $request)
    {
        $users = User::all();
        dd($users[0]->id);
        ?><document type="freeswitch/xml">
                <section name="directory">
                    <domain name="example.com">
                        <params>
                            <param name="dial-string" value="{presence_id=${dialed_user}@${dialed_domain}}${sofia_contact(${dialed_user}@${dialed_domain})}"/>
                        </params>
                        <groups>
                            <group name="default">
                                <users>
                                    <?php
                                        foreach($users as $user) {
                                    ?>
                                    <user id="UR<?php echo $user->id; ?>">
                                        <params>
                                            <param name="password" value="123456"/>
                                        </params>
                                        <variables>
                                            <variable name="accountcode" value="<?php echo $user['number']; ?>"/>
                                            <variable name="user_context" value="public"/>
                                            <variable name="effective_caller_id_name" value="<?php echo $user['name']; ?>"/>
                                            <variable name="effective_caller_id_number" value="<?php echo $user['number']; ?>"/>
                                            <variable name="outbound_caller_id_name" value="<?php echo $user['name']; ?>"/>
                                            <variable name="outbound_caller_id_number" value="<?php echo $user['number']; ?>"/>
                                            <variable name="mySuperVariable" value="<?php echo $user['myVar']; ?>" />
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
    }

    public function appointmentTotal(Request $request)
    {
        return (new TimelineService)->appointmentTotal($request);
    }
}
