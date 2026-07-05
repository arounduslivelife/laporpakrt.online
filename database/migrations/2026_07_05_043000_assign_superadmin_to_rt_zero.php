<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Rt;
use App\Models\User;
use App\Models\Region;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Database\Eloquent\Model::unguard();

        // 1. Create a dummy village/region if none exists, or use the first one.
        $region = Region::firstOrCreate(
            ['code' => '00000000'],
            ['name' => 'Kelurahan Dummy', 'type' => 'VILLAGE']
        );

        // 2. Create RT 0 / RW 0
        $rt = Rt::firstOrCreate(
            ['village_code' => $region->code, 'rw' => '0', 'rt' => '0'],
            []
        );

        // 3. Assign superadmin to this RT
        User::where('email', 'aroundus.livelife@gmail.com')->update(['rt_id' => $rt->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $rt = Rt::where('rw', '0')->where('rt', '0')->first();
        if ($rt) {
            User::where('email', 'aroundus.livelife@gmail.com')->update(['rt_id' => null]);
            $rt->delete();
        }
    }
};
