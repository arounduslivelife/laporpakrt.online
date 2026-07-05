<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscriptions and delete RT data if expired > 2 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSubscriptions = \App\Models\Subscription::where('end_date', '<', now()->subDays(2))->get();

        foreach ($expiredSubscriptions as $sub) {
            $rt = \App\Models\Rt::find($sub->rt_id);
            if ($rt) {
                // Delete related data (assuming relationships have cascade deletes, if not, delete manually)
                // Warga, Tamu, Kas, Surat, Users
                \App\Models\User::where('rt_id', $rt->id)->delete();
                \App\Models\Warga::where('rt_id', $rt->id)->delete();
                \App\Models\Tamu::where('rt_id', $rt->id)->delete();
                \App\Models\Kas::where('rt_id', $rt->id)->delete();
                \App\Models\Surat::where('rt_id', $rt->id)->delete();
                
                // Finally delete subscription and RT
                $sub->delete();
                $rt->delete();
                
                $this->info("Deleted RT ID: {$rt->id} due to expired subscription.");
            }
        }
        
        $this->info("Subscription check completed.");
    }
}
