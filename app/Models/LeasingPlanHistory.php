<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeasingPlanHistory extends Model
{
    protected $table = 'leasing_plan_history';

    public function leasingPlan()
    {
        return $this->belongsTo(LeasingPlan::class,'id','leasing_plan_id');
    }
}
