<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;
use App\Transformers\Provider\ProviderTransformer;



class ProviderService
{

    public function store($request)
    {
        try {
            $input = $request->only(['name', 'address', 'countryId', 'stateId', 'city', 'zipcode', 'phoneNumber', 'tagId', 'moduleId', 'isActive']);
            $otherData = [
                'udid' => Str::uuid()->toString(),
                'createdBy' => 1
            ];
            $data = JSON_ENCODE(array_merge(
                $input,
                $otherData
            ));
            DB::select(
                "CALL addProvider('" . $data . "')"
            );
            return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function providerLocationStore($request, $id)
    {
        try {
            $input = $request->only(['locationName', 'locationAddress', 'numberOfLocations', 'stateId', 'city', 'zipCode', 'phoneNumber', 'email', 'websiteUrl', 'isActive', 'isDefault']);
            $otherData = [
                'udid' => Str::uuid()->toString(),
                'providerId'=>$id,
                'createdBy' => 1
            ];
            $data = JSON_ENCODE(array_merge(
                $input,
                $otherData
            ));
            DB::select(
                "CALL addProviderLocations('" . $data . "')"
            );
            return response()->json(['message' => trans('messages.createdSuccesfully')], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function index(){
        $data = Provider::all();
        return fractal()->collection($data)->transformWith(new ProviderTransformer())->toArray();
    }
}
