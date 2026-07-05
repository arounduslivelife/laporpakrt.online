<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Rt;
use App\Traits\DetectsMobile;
use Carbon\Carbon;

class SubscriptionManagement extends Component
{
    use DetectsMobile;

    public $search = '';

    public function mount()
    {
        abort_if(!auth()->user()->isSuperadmin(), 403);
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

    public function render()
    {
        $query = Subscription::with(['rt.village.district.regency', 'plan']);

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

        if ($this->isMobile()) {
            return view('livewire.mobile.subscription-management', compact('subscriptions'))
                ->layout('layouts.mobile', ['title' => 'Manajemen Langganan']);
        }

        return view('livewire.subscription-management', compact('subscriptions'))
            ->layout('layouts.app', ['title' => 'Manajemen Langganan']);
    }
}
