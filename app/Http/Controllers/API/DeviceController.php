<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivationCode;
use App\Models\Device;
use App\Models\LeasingPlanHistory;
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
    public function update($deviceId)
    {
        //
    }

    /**
     * Retrieve device information
     */
    public function info($deviceId)
    {
        $device = Device::with('owner','leasingPlan','leasingPlanHistory')
                        ->where('device_id',$deviceId)
                        ->firstOrFail();

        if(in_array($device->type,['free','unset'])) {
            return responder()->success([
                'deviceId'          => $device->device_id,
                'deviceType'        => $device->type,
                'leasingPeriods'    => [],
                'timestamp'         => date('Y-m-d'),
            ])->respond(Response::HTTP_OK);
        }

        $response = [
            'deviceId'          => $device->device_id,
            'deviceType'        => $device->type,
            'deviceOwner'       => $device->owner->name,
            'deviceOwnerDetails' => [
                'billing_name'      => $device->owner->billing_name,
                'address_country'   => $device->owner->address_country,
                'address_zip'       => $device->owner->address_zip,
                'address_city'      => $device->owner->address_city,
                'address_street'    => $device->owner->address_street,
                'vat_number'        => $device->owner->vat_number,
            ],
            'dateofRegistration' => $device->registered_at,
            'leasingPeriodsComputed' => [
                'leasingConstructionId'                 => $device->leasingPlan->id,
                'leasingConstructionMaximumTraining'    => $device->leasingPlan->maximum_trainings,
                'leasingConstructionMaximumDate'        => $device->leasingPlan->maximum_date,
                'leasingActualPeriodStartDate'          => $device->leasingPlan->actual_period_start_date,
                'leasingNextCheck'                      => $device->leasingPlan->next_check_at,
            ],
            'leasingPeriods' => $this->getLeasingPeriodHistory($device->leasingPlanHistory),
            'timestamps'     => date('Y-m-d H:i:s'),
        ];

        return responder()->success($response)->respond(Response::HTTP_OK);
    }

    private function getLeasingPeriodHistory($history_records)
    {
        $records = [];

        foreach($history_records as $record){
            $records[] = [
                'leasingConstructionId'                 => $record->leasingPlan->id,
                'leasingConstructionMaximumTraining'    => $record->leasingPlan->maximum_trainings,
                'leasingConstructionMaximumDate'        => $record->leasingPlan->maximum_date,
            ];
        }

        return $records;
    }
}
