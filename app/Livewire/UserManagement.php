<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;
use App\Traits\DetectsMobile;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use DetectsMobile;

    public $name, $email, $password, $role = 'warga', $warga_id;
    public $showForm = false;

    public function mount()
    {
        abort_if(!in_array(auth()->user()->role, ['superadmin', 'rt_admin']), 403);
    }

    public function save()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:warga,security',
            'warga_id' => [
                'required_if:role,warga',
                Rule::exists('wargas', 'id')->where(function ($query) {
                    return $query->where('rt_id', auth()->user()->rt_id);
                }),
            ],
            'name' => 'required_if:role,security',
        ]);

        $userData = [
            'email' => $this->email,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
            'role' => $this->role,
            // If superadmin creates, they might not have rt_id.
            'rt_id' => auth()->user()->rt_id,
        ];

        if ($this->role == 'warga') {
            $warga = Warga::find($this->warga_id);
            $userData['name'] = $warga->name;
            $userData['warga_id'] = $warga->id;
        } else {
            $userData['name'] = $this->name;
        }

        User::create($userData);

        $this->reset(['name', 'email', 'password', 'role', 'warga_id', 'showForm']);
        $this->role = 'warga';
        $this->dispatch('notify', ['type' => 'success', 'message' => 'User berhasil ditambahkan!']);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        // RT Admin can only delete users in their RT. Superadmin can delete anyone.
        if (!auth()->user()->isSuperadmin() && $user->rt_id != auth()->user()->rt_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->id == auth()->id()) {
            abort(403, 'Cannot delete yourself.');
        }

        $user->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'User berhasil dihapus!']);
    }

    public $search = '';

    public function render()
    {
        // Superadmin sees all users, RT Admin sees their own RT
        $query = User::with('rt.village')->where('id', '!=', auth()->id());
        
        if (!auth()->user()->isSuperadmin()) {
            $query->where('rt_id', auth()->user()->rt_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('rt', function($q) {
                      $q->where('rt', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        $users = $query->get();
        $wargas = Warga::where('rt_id', auth()->user()->rt_id)->get();
        
        if ($this->isMobile()) {
            return view('livewire.mobile.user-management', compact('users', 'wargas'))
                ->layout('layouts.mobile', ['title' => 'Manajemen Akses']);
        }
        
        return view('livewire.user-management', compact('users', 'wargas'))
            ->layout('layouts.app', ['title' => 'Manajemen Akses']);
    }
}
