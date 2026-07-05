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
    
    // For editing plans
    public $showPlanModal = false;
    public $editingPlanId;
    public $planName;
    public $planPrice;
    public $planDuration;

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
