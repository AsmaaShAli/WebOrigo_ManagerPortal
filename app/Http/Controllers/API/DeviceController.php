<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivationCode;
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
        $device = Device::where('device_id',$request->deviceId)->firstOrFail();
        $remember_token = Str::random(30);

        $device->update([
            'remember_token'  => $remember_token,
        ]);

        if(! $request->activationCode) {
            if (!$device->activation_code && $device->type == 'unset') {
                $device->update([
                    'type' => 'free',
                ]);
            }

            return responder()->success([
                'deviceId'      => $device->device_id,
                'deviceAPIKey'  => $device->remember_token,
                'deviceType'    => $device->type,
                'timestamp'     => date('Y-m-d H:i:s'),
            ])->respond(Response::HTTP_OK);
        }

        $activation_code_taken = $device->isActivationCodeTaken($request->activationCode);

        if ($activation_code_taken) {
            return responder()->error(422, 'Invalid activation code.');
        }

        $leasing_plan_id = ActivationCode::where('code',$request->activationCode)->first()->leasing_plan_id;

        $device->update([
            'type'              => 'leasing',
            'activation_code'   => $request->activationCode,
            'leasing_plan_id'   => $leasing_plan_id,
            'registered_at'     => date('Y-m-d H:i:s'),
        ]);

        return responder()->success([
            'deviceId'      => $device->device_id,
            'deviceAPIKey'  => $device->remember_token,
            'deviceType'    => $device->type,
            'timestamp'     => date('Y-m-d'),
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
