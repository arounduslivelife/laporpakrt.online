<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    public $role = 'warga', $name, $email, $password, $warga_id;
    public $showForm = false;
    
    public function mount()
    {
        abort_if(auth()->user()->role != 'rt_admin', 403);
    }
    
    public function save()
    {
        $this->validate([
            'role' => 'required|in:warga,security',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        
        if ($this->role == 'warga') {
            $this->validate(['warga_id' => 'required|exists:wargas,id']);
            $warga = Warga::find($this->warga_id);
            $this->name = $warga->name;
        } else {
            $this->validate(['name' => 'required|string']);
        }
        
        User::create([
            'rt_id' => auth()->user()->rt_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'warga_id' => $this->role == 'warga' ? $this->warga_id : null,
        ]);
        
        $this->reset(['role', 'name', 'email', 'password', 'warga_id', 'showForm']);
        $this->role = 'warga';
    }
    
    public function delete($id)
    {
        User::where('rt_id', auth()->user()->rt_id)->where('id', '!=', auth()->id())->findOrFail($id)->delete();
    }

    public function render()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->where('id', '!=', auth()->id())->get();
        $wargas = Warga::where('rt_id', auth()->user()->rt_id)->get();
        
        return view('livewire.user-management', compact('users', 'wargas'))
            ->layout('layouts.app', ['title' => 'Manajemen Akses']);
    }
}
