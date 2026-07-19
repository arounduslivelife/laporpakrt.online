<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Rt;
use App\Models\Region;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\DetectsMobile;

class Register extends Component
{
    use DetectsMobile;

    public $name, $email, $password, $password_confirmation;
    public $province_id, $regency_id, $district_id, $village_id;
    public $rw, $rt;

    public $provinces = [], $regencies = [], $districts = [], $villages = [];

    public function mount()
    {
        $this->provinces = Region::where('type', 'PROVINCE')->get();
    }

    public function updatedProvinceId($value)
    {
        $this->regencies = Region::where('parent_code', $value)->get();
        $this->regency_id = null;
        $this->districts = [];
        $this->villages = [];
    }

    public function updatedRegencyId($value)
    {
        $this->districts = Region::where('parent_code', $value)->get();
        $this->district_id = null;
        $this->villages = [];
    }

    public function updatedDistrictId($value)
    {
        $this->villages = Region::where('parent_code', $value)->get();
        $this->village_id = null;
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'village_id' => 'required',
            'rw' => 'required|max:3',
            'rt' => 'required|max:3',
        ]);

        $formattedRw = str_pad($this->rw, 3, '0', STR_PAD_LEFT);
        $formattedRt = str_pad($this->rt, 3, '0', STR_PAD_LEFT);

        // Cek duplikasi RT
        $exists = Rt::where('village_code', $this->village_id)
                    ->where('rw', $formattedRw)
                    ->where('rt', $formattedRt)
                    ->exists();
        
        if ($exists) {
            $this->addError('rt', 'RT ini sudah terdaftar di sistem. Hubungi Super Admin jika ini adalah kesalahan.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'rt_admin',
        ]);

        $rt = Rt::create([
            'village_code' => $this->village_id,
            'rw' => $formattedRw,
            'rt' => $formattedRt,
            'admin_id' => $user->id,
        ]);

        $user->update(['rt_id' => $rt->id]);

        // Berikan langganan Gratis 7 Hari secara otomatis
        $freePlan = \App\Models\SubscriptionPlan::where('price', 0)->first();
        if ($freePlan) {
            \App\Models\Subscription::create([
                'rt_id' => $rt->id,
                'plan_id' => $freePlan->id,
                'start_date' => now(),
                'end_date' => now()->addDays($freePlan->duration_days),
                'status' => 'active',
            ]);
        }

        Auth::login($user);
        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        if ($this->isMobile()) {
            return view('livewire.mobile.auth.register')->layout('layouts.mobile-auth', ['title' => 'Daftar - LaporPakRT']);
        }

        return view('livewire.auth.register')->layout('layouts.auth');
    }
}
