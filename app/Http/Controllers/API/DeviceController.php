<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    /**
     * register a new device
     */
    public function register(Request $request)
    {
        $device = Device::findorFail($request->deviceId);

        if(! $request->activationCode) {
            if (!$device->activation_code && $device->type == 'unset'){
                $device->type = 'free';
            }

            $device->remember_token = Str::random(20);

            return responder()->success([
                'deviceId'      => $device->id,
                'deviceAPIKey'  => $device->remember_token,
                'deviceType'    => $device->type,
                'timestamp'     => date('Y-m-d H:i:s'),
            ])->respond(Response::HTTP_OK);
        }

        $activation_code_taken = Device::where('id','<>',$device->id)
                                ->where('activation_code',$request->activationCode)
                                ->whereIn('type',['free','leasing'])
                                ->exists();

        if ($activation_code_taken) {
            return responder()->error(422, 'Invalid activation code.');
        }

        $device->update([
            'type'              => 'leasing',
            'activation_code'   => $request->activationCode,
            'remember_token'    => Str::random(20),
        ]);

        return responder()->success([
            'deviceId'      => $device->id,
            'deviceAPIKey'  => $device->remember_token,
            'deviceType'    => $device->type,
            'timestamp'     => date('Y-m-d H:i:s'),
        ])->respond(Response::HTTP_OK);
    }

    /**
     * Update device's Leasing Period
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Retrieve device information
     */
    public function info()
    {

    }
}
