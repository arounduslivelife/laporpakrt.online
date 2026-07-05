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
            
            // Bypass RT 000
            if ($rt && $rt->rt == '000' && $rt->rw == '000') {
                continue;
            }

            if ($rt) {
                // Delete related data with loop to trigger deleting events for files
                $users = \App\Models\User::where('rt_id', $rt->id)->get();
                foreach($users as $user) { $user->delete(); }
                
                $wargas = \App\Models\Warga::where('rt_id', $rt->id)->get();
                foreach($wargas as $warga) { $warga->delete(); }
                
                $tamus = \App\Models\Tamu::where('rt_id', $rt->id)->get();
                foreach($tamus as $tamu) { $tamu->delete(); }
                
                $kasTransactions = \App\Models\KasTransaction::where('rt_id', $rt->id)->get();
                foreach($kasTransactions as $kas) { $kas->delete(); }
                
                $surats = \App\Models\Surat::where('rt_id', $rt->id)->get();
                foreach($surats as $surat) { $surat->delete(); }
                
                // Finally delete subscription and RT
                $sub->delete();
                $rt->delete();
                
                $this->info("Deleted RT ID: {$rt->id} due to expired subscription.");
            }
        }
        
        $this->info("Subscription check completed.");
    }
}
