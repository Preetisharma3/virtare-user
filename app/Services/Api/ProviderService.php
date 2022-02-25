<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
           $id =  DB::select(
                "CALL addProvider('" . $data . "')"
            );
            foreach($id as $providerId){
                $provider = Provider::where('id',$providerId->id)->first();
            }
            $userdata = fractal()->item($provider)->transformWith(new ProviderTransformer())->toArray();
            $message = ['message' => trans('messages.createdSuccesfully')];
            $endData = array_merge($message, $userdata);
            return $endData;
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

    public function providerLocationList($request,$id){
        $data = Provider::where('providerId',$id)->get();
        return $data;
    }

    public function index(){
        $data = Provider::all();
        return fractal()->collection($data)->transformWith(new ProviderTransformer())->toArray();
    }
}
