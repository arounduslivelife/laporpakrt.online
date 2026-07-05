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
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized action. Superadmin only.');
        }
    }
    
    public function save()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized action. Superadmin only.');
        }

        $this->validate([
            'role' => 'required|in:rt_admin,warga,security',
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
            'rt_id' => auth()->user()->rt_id, // superadmin can have null rt_id, which means system-wide user
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
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized action. Superadmin only.');
        }
        User::where('id', '!=', auth()->id())->findOrFail($id)->delete();
    }

    public function render()
    {
        // Superadmin sees all users
        $users = User::where('id', '!=', auth()->id())->get();
        $wargas = Warga::all();
        
        return view('livewire.user-management', compact('users', 'wargas'))
            ->layout('layouts.app', ['title' => 'Manajemen Akses']);
    }
}
