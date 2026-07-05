<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Rt;
use App\Traits\DetectsMobile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionManagement extends Component
{
    use DetectsMobile;

    public $search = '';
    
    // For editing plans
    public $showPlanModal = false;
    public $editingPlanId;
    public $planName;
    public $planPrice;
    public $planDuration;

    public $showRtModal = false;
    public $rtDetails = null;

    public function mount()
    {
        abort_if(!auth()->user()->isSuperadmin(), 403);
    }

    public function editPlan($id)
    {
        $plan = \App\Models\SubscriptionPlan::findOrFail($id);
        $this->editingPlanId = $plan->id;
        $this->planName = $plan->name;
        $this->planPrice = $plan->price;
        $this->planDuration = $plan->duration_days;
        $this->showPlanModal = true;
    }

    public function updatePlan()
    {
        $this->validate([
            'planName' => 'required|string|max:255',
            'planPrice' => 'required|numeric|min:0',
            'planDuration' => 'required|integer|min:1',
        ]);

        $plan = \App\Models\SubscriptionPlan::findOrFail($this->editingPlanId);
        $plan->update([
            'name' => $this->planName,
            'price' => $this->planPrice,
            'duration_days' => $this->planDuration,
        ]);

        $this->showPlanModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Paket langganan berhasil diperbarui.']);
    }

    public function activateSubscription($subscriptionId)
    {
        abort_if(!auth()->user()->isSuperadmin(), 403);
        $sub = Subscription::findOrFail($subscriptionId);
        
        $duration = $sub->plan->duration_days;
        
        $sub->update([
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($duration),
        ]);
        
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Langganan berhasil diaktifkan/diperpanjang.']);
    }

    public function showRtDetails($rtId)
    {
        $rt = Rt::with('village.parent.parent')->findOrFail($rtId);
        
        $totalWarga = \App\Models\Warga::where('rt_id', $rtId)->count();
        $totalTamu = \App\Models\Tamu::where('rt_id', $rtId)->count();
        $tamuBulanIni = \App\Models\Tamu::where('rt_id', $rtId)->whereMonth('created_at', date('m'))->count();
        
        $pemasukan = \App\Models\KasTransaction::where('rt_id', $rtId)->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = \App\Models\KasTransaction::where('rt_id', $rtId)->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoKas = $pemasukan - $pengeluaran;
        
        $lastActivityTimestamp = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('users.rt_id', $rtId)
            ->max('sessions.last_activity');
            
        $lastOnline = $lastActivityTimestamp 
            ? Carbon::createFromTimestamp($lastActivityTimestamp)->diffForHumans() 
            : 'Belum pernah login';

        $this->rtDetails = [
            'rt_rw' => 'RT ' . $rt->rt . ' / RW ' . $rt->rw,
            'village' => $rt->village->name ?? '-',
            'total_warga' => $totalWarga,
            'total_tamu' => $totalTamu,
            'tamu_bulan_ini' => $tamuBulanIni,
            'saldo_kas' => $saldoKas,
            'last_online' => $lastOnline,
        ];
        
        $this->showRtModal = true;
    }

    public function closeRtDetails()
    {
        $this->showRtModal = false;
        $this->rtDetails = null;
    }

    public function render()
    {
        $query = Subscription::with(['rt.village.parent.parent', 'plan']);

        if (!empty($this->search)) {
            $query->whereHas('rt', function($q) {
                $q->where('rt', 'like', '%' . $this->search . '%')
                  ->orWhere('rw', 'like', '%' . $this->search . '%')
                  ->orWhereHas('village', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $subscriptions = $query->orderBy('end_date', 'asc')->get();
        $plans = \App\Models\SubscriptionPlan::all();

        if ($this->isMobile()) {
            return view('livewire.mobile.subscription-management', compact('subscriptions', 'plans'))
                ->layout('layouts.mobile', ['title' => 'Manajemen Langganan']);
        }

        return view('livewire.subscription-management', compact('subscriptions', 'plans'))
            ->layout('layouts.app', ['title' => 'Manajemen Langganan']);
    }
}
