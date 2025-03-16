<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    protected $guarded = ['device_id'];
    public function leasingPlan()
    {
        return $this->hasOne(\App\Models\LeasingPlan::class, 'id', 'leasing_plan_id');
    }

    public function leasingPlanHistory()
    {
        return $this->hasMany(\App\Models\LeasingPlanHistory::class, 'id', 'device_id');
    }

    public function Owner()
    {
        return $this->hasOne(\App\Models\Owner::class, 'id', 'owner_id');
    }
    public function isActivationCodeTaken($activationCode)
    {
        return $this->where('id','<>',$this->id)
            ->where('activation_code',$activationCode)
            ->whereIn('type',['free','leasing'])
            ->exists();
    }

}
