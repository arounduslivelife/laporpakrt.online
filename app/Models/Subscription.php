<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['rt_id', 'plan_id', 'start_date', 'end_date', 'status', 'payment_proof'])]
class Subscription extends Model
{
    public function rt()   { return $this->belongsTo(Rt::class); }
    public function plan() { return $this->belongsTo(SubscriptionPlan::class); }
}
