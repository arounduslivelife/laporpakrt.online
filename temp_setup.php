<?php
$sa = App\Models\User::where('email', 'aroundus.livelife@gmail.com')->first();
$villageCode = App\Models\Region::where('type', 'VILLAGE')->first()->code;
$rt = App\Models\Rt::firstOrCreate(
    ['rw' => '000', 'rt' => '000'],
    ['village_code' => $villageCode, 'admin_id' => $sa->id]
);
$sa->update(['rt_id' => $rt->id]);
$plan = App\Models\SubscriptionPlan::where('price', 0)->first();
App\Models\Subscription::updateOrCreate(
    ['rt_id' => $rt->id],
    ['plan_id' => $plan->id, 'start_date' => now(), 'end_date' => now()->addYears(100), 'status' => 'active']
);
echo "RT 000 created and assigned successfully.\n";
