<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class ProviderService
{

    public function store($request)
    {
        try {
            $udid = Str::uuid()->toString();
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
            DB::select(
                'CALL addProvider
            (
            "' . $udid . '","' . $name . '","' . $address . '",
            "' . $countryId . '","' . $stateId . '","' . $city . '",
            "' . $zipcode . '","' . $phoneNumber . '","' . $tagId . '",
            "' . $moduleId . '","' . $isActive . '","' . $createdBy . '"
            )'
            );
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function providerLocationStore($request, $id)
    {
        try {
            $udid = Str::uuid()->toString();
            $providerId = $id;
            $locationName = $request->locationName;
            $locationAddress = $request->locationAddress;
            $numberOfLocations = $request->numberOfLocations;
            $stateId = $request->stateId;
            $city = $request->city;
            $zipCode = $request->zipCode;
            $phoneNumber = $request->phoneNumber;
            $email = $request->email;
            $websiteUrl = $request->websiteUrl;
            $isActive = $request->isActive;
            $isDefault = $request->isDefault;
            $createdBy = 1;
            DB::select(
                'CALL addProviderLocations("' . $udid . '","' . $providerId . '","' . $locationName . '","' . $numberOfLocations . '","' . $locationAddress . '","' . $stateId . '","' . $city . '","' . $zipCode . '","' . $phoneNumber . '","' . $email . '","' . $websiteUrl . '","' . $isDefault . '","' . $isActive . '","' . $createdBy . '")'
            );
            return response()->json(['message' => 'Created Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
