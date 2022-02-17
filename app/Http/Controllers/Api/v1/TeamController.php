<?php

namespace App\Http\Controllers\Api\v1;


use Illuminate\Http\Request;
use App\Services\Api\TeamService;
use App\Http\Controllers\Controller;


class TeamController extends Controller
{
    public function team(Request $request,$patientId = null,$type,$id = null){
        return (new TeamService)->team($request,$patientId, $type, $id);
    }

    public function all(Request $request,$patientId)
    {
        return  [
            "data" => [
                "staff" => (new TeamService)->team($request,$patientId = null, "staff", null),
                "physician" => (new TeamService)->team($request,$patientId = null, "physician", null),
                "familyMember" => (new TeamService)->team($request,$patientId = null,"familyMember", null)
            ]
        ];
    }
}
