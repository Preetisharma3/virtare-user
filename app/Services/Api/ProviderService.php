<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class ProviderService
{

    public function store($request){
        try {
            $udid =Str::uuid()->toString();
            $name = $request->name;
            $address = $request->address;
            $countryId = $request->countryId;
            $stateId = $request->stateId;
            $city = $request->city;
            $zipcode = $request->zipcode;
            $phoneNumber = $request->phoneNumber;
            $tagId = $request->tagId;
            $moduleId = $request->moduleId;
            $isActive = $request->isActive;
            $createdBy = 1;
            DB::select('CALL addProvider("' . $udid . '","' . $name . '","' . $address . '","' . $countryId . '","' . $stateId . '","' . $city . '","' . $zipcode . '","' . $phoneNumber . '","' . $tagId . '","' . $moduleId . '","' . $isActive . '","' . $createdBy . '")');
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
