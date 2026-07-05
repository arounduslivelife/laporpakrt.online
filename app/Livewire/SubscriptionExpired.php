<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\DetectsMobile;

class SubscriptionExpired extends Component
{
    use DetectsMobile;

    public function render()
    {
        if ($this->isMobile()) {
            return view('livewire.mobile.subscription-expired')->layout('layouts.mobile', ['title' => 'Langganan Berakhir']);
        }

        return view('livewire.subscription-expired')->layout('layouts.app', ['title' => 'Langganan Berakhir']);
    }
}
