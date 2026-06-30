<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Surat;

class SuratIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10;
    
    public function mount()
    {
        abort_if(auth()->user()->role != 'rt_admin', 403);
    }
    
    public function updatingSearch() { $this->resetPage(); }
    
    public function delete($id)
    {
        Surat::where('rt_id', auth()->user()->rt_id)->findOrFail($id)->delete();
    }
    
    public function render()
    {
        $surats = auth()->user()->rt->surats()
            ->with('warga')
            ->when($this->search, fn($q) => $q->where('nomor_surat', 'like', "%{$this->search}%")->orWhereHas('warga', fn($q) => $q->where('name', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate($this->perPage);
            
        return view('livewire.surat-index', compact('surats'))
            ->layout('layouts.app', ['title' => 'Dokumen & Surat']);
    }
}
