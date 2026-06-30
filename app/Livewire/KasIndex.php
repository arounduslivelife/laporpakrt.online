<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\KasTransaction;
use App\Models\Warga;

class KasIndex extends Component
{
    use WithPagination;

    public $tipe = 'pemasukan', $jumlah, $kategori, $keterangan, $warga_id, $periode_bulan;
    public $showForm = false;
    
    public function mount()
    {
        abort_if(auth()->user()->role != 'rt_admin', 403);
    }
    
    public $search = '';
    public $filterTipe = '';
    public $perPage = 10;
    
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterTipe() { $this->resetPage(); }

    public function resetForm()
    {
        $this->reset(['tipe', 'jumlah', 'kategori', 'keterangan', 'warga_id', 'periode_bulan', 'showForm']);
        $this->tipe = 'pemasukan';
    }

    public function save()
    {
        $this->validate([
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:1',
            'kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'warga_id' => 'nullable|exists:wargas,id',
            'periode_bulan' => 'nullable|date_format:Y-m',
        ]);

        KasTransaction::create([
            'rt_id' => auth()->user()->rt_id,
            'tipe' => $this->tipe,
            'jumlah' => $this->jumlah,
            'kategori' => $this->kategori,
            'keterangan' => $this->keterangan,
            'warga_id' => $this->warga_id,
            'periode_bulan' => $this->periode_bulan,
        ]);
        
        $this->resetForm();
    }

    public function delete($id)
    {
        KasTransaction::where('rt_id', auth()->user()->rt_id)->findOrFail($id)->delete();
    }

    public function render()
    {
        $transactions = auth()->user()->rt->kasTransactions()
            ->with('warga')
            ->when($this->search, fn($q) => $q->where('kategori', 'like', "%{$this->search}%")->orWhere('keterangan', 'like', "%{$this->search}%"))
            ->when($this->filterTipe, fn($q) => $q->where('tipe', $this->filterTipe))
            ->latest()
            ->paginate($this->perPage);

        $pemasukan = auth()->user()->rt->kasTransactions()->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = auth()->user()->rt->kasTransactions()->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        $wargas = Warga::where('rt_id', auth()->user()->rt_id)->get();

        return view('livewire.kas-index', compact('transactions', 'saldo', 'wargas'))
            ->layout('layouts.app', ['title' => 'Manajemen Kas RT']);
    }
}
