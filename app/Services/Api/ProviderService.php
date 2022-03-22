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
                'createdBy' => Auth::id()
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

    public function index($request, $id)
    {
        if (!$id) {
            if ($request->all) {
                if ($request->active == 1) {
                    $data = Provider::all();
                } else {
                    $data = Provider::where('isActive', 1)->get();
                }
                return fractal()->collection($data)->transformWith(new ProviderTransformer())->toArray();
            } else {
                if ($request->active) {
                    $data = Provider::paginate(env('PER_PAGE', 20));
                } else {
                    $data = Provider::where('isActive', 1)->paginate(env('PER_PAGE', 20));
                }
                return fractal()->collection($data)->transformWith(new ProviderTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            }
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
        $provider = array();
        if (!empty($request->name)) {
            $provider['name'] = $request->name;
        }
        if (!empty($request->address)) {
            $provider['address'] = $request->address;
        }
        if (!empty($request->countryId)) {
            $provider['countryId'] = $request->countryId;
        }
        if (!empty($request->stateId)) {
            $provider['stateId'] = $request->stateId;
        }
        if (!empty($request->city)) {
            $provider['city'] = $request->city;
        }
        if (!empty($request->zipCode)) {
            $provider['zipCode'] = $request->zipCode;
        }
        if (!empty($request->phoneNumber)) {
            $provider['phoneNumber'] = $request->phoneNumber;
        }
        if (!empty($request->tagId)) {
            $provider['tagId'] = $request->tagId;
        }
        if (!empty($request->moduleId)) {
            $provider['moduleId'] = $request->moduleId;
        }
        if (isset($request->isActive)) {
            $provider['isActive'] = $request->isActive;
        }
        $provider['updatedBy'] = Auth::id();
        Provider::where('id', $id)->update($provider);
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
}
