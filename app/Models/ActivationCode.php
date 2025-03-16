<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    protected $table = 'activation_codes';

    public function LeasingPlan()
    {
        return $this->belongsTo(\App\Models\LeasingPlan::class, 'leasing_plan_id', 'id');
    }
}
