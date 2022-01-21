<?php

namespace App\Http\Controllers\Api\v1;


use Illuminate\Http\Request;
use App\Services\Api\TeamService;
use App\Http\Controllers\Controller;


class TeamController extends Controller
{
    public function team(Request $request,$type,$id = null){
        return (new TeamService)->team($request, $type, $id);
    }

    public function all(Request $request)
    {
        return  [
            "data" => [
                "staff" => (new TeamService)->team($request, "staff", null),
                "physician" => (new TeamService)->team($request, "physician", null),
                "familyMember" => (new TeamService)->team($request, "familyMember", null)
            ]
        ];
    }
}
