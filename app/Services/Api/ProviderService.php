<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Support\Str;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Provider\ProviderLocation;
use App\Transformers\Provider\ProviderTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Provider\ProviderLocationTransformer;

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
            foreach ($id as $providerId) {
                $provider = Provider::where('id', $providerId->id)->first();
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
                'providerId' => $id,
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

    public function index($id)
    {
        if (!$id) {
            $data = Provider::paginate(env('PER_PAGE', 20));
            return fractal()->collection($data)->transformWith(new ProviderTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        } elseif ($id) {
            $data = Provider::where('id', $id)->first();
            return fractal()->item($data)->transformWith(new ProviderTransformer())->toArray();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
    }

    public function editLocation($id, $locationId)
    {
        if (!$locationId) {
            $data = ProviderLocation::where('providerId', $id)->get();
            return fractal()->collection($data)->transformWith(new ProviderLocationTransformer())->toArray();
        } elseif ($locationId) {
            $data = ProviderLocation::where([['id', $locationId], ['providerId', $id]])->first();
            return fractal()->item($data)->transformWith(new ProviderLocationTransformer())->toArray();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
    }



    public function updateProvider($request, $id)
    {
        $input = [
            'name' => $request->name, 'address' => $request->address, 'countryId' => $request->countryId,
            'stateId' => $request->stateId, 'city' => $request->city, 'zipCode' => $request->zipCode, 'phoneNumber' => $request->phoneNumber, 'tagId' => $request->tagId, 'moduleId' => $request->moduleId,
            'isActive' => $request->isActive,
        ];
        Provider::where('id', $id)->update($input);
        $enddata = Provider::where('id', $id)->first();
        $message = ['message' => trans('messages.updatedSuccesfully')];
        $data = fractal()->item($enddata)->transformWith(new ProviderTransformer())->toArray();
        return array_merge($message, $data);
    }

    public function updateLocation($request, $id, $locationId)
    {
        $input = [
            'locationName' => $request->locationName, 'locationAddress' => $request->locationAddress, 'numberOfLocations' => $request->numberOfLocations,
            'stateId' => $request->stateId, 'city' => $request->city, 'zipCode' => $request->zipCode, 'phoneNumber' => $request->phoneNumber, 'email' => $request->email,
            'websiteUrl' => $request->websiteUrl, 'isActive' => $request->isActive, 'updatedBy' => Auth::id(),
        ];
        ProviderLocation::where('id', $id)->update($input);
        $enddata = ProviderLocation::where('id', $id)->first();
        $message = ['message' => trans('messages.updatedSuccesfully')];
        $data = fractal()->item($enddata)->transformWith(new ProviderLocationTransformer())->toArray();
        return array_merge($message, $data);
    }

    public function deleteProviderLocation($id, $locationId)
    {
        if (!$locationId) {
            Provider::where('id', $id)->update([
                'isActive' => 0, 'isDelete' => 1, 'deletedBy' => Auth::id()
            ]);
            ProviderLocation::where('providerId', $id)->update([
                'isActive' => 0, 'isDelete' => 1, 'deletedBy' => Auth::id()
            ]);
            Provider::where('id', $id)->delete();
            ProviderLocation::where('providerId', $id)->delete();
        } elseif ($locationId) {
            ProviderLocation::where([['providerId', $id], ['id', $locationId]])->update([
                'isActive' => 0, 'isDelete' => 1, 'deletedBy' => auth()->user()->id
            ]);
            ProviderLocation::where([['providerId', $id], ['id', $locationId]])->delete();
        } else {
            return response()->json(['message' => trans('messages.unauthenticated')], 401);
        }
        return response()->json(['message' => trans('messages.deletedSuccesfully')], 200);
    }

    // public function providerUpdate($request, $id)
    // {
    //     try {
    //         $input = [
    //             'name', 'address', 'countryId', 'stateId', 'city', 'zipcode', 'phoneNumber', 'tagId', 'moduleId', 'isActive', 'updatedBy' => 1
    //         ];
    //         Provider::where('udid', $id)->update($input);
    //         $provider = Provider::where('udid', $id)->first();
    //         $userdata = fractal()->item($provider)->transformWith(new ProviderTransformer())->toArray();
    //         $message = ['message' => trans('messages.createdSuccesfully')];
    //         $endData = array_merge($message, $userdata);
    //         return $endData;
    //     } catch (Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
}
