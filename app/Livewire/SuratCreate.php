<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Warga;
use App\Models\Surat;
use Illuminate\Support\Str;

class SuratCreate extends Component
{
    public $warga_id, $jenis = 'pengantar_umum', $keperluan;
    
    public function mount()
    {
        abort_if(auth()->user()->role != 'rt_admin', 403);
    }
    
    public function save()
    {
        $this->validate([
            'warga_id' => 'required|exists:wargas,id',
            'jenis' => 'required|string',
            'keperluan' => 'required|string',
        ]);
        
        $rt = auth()->user()->rt;
        $count = Surat::where('rt_id', $rt->id)->count() + 1;
        $nomorSurat = sprintf("%03d/RT%03d-RW%03d/%s", $count, $rt->rt, $rt->rw, date('Y'));
        
        $surat = Surat::create([
            'rt_id' => $rt->id,
            'warga_id' => $this->warga_id,
            'nomor_surat' => $nomorSurat,
            'jenis' => $this->jenis,
            'keperluan' => $this->keperluan,
            'status' => 'ditandatangani',
            'qrcode_token' => Str::random(32),
            'signed_at' => now(),
        ]);
        
        return redirect()->route('surat.index');
    }
    
    public function render()
    {
        $wargas = Warga::where('rt_id', auth()->user()->rt_id)->get();
        return view('livewire.surat-create', compact('wargas'))
            ->layout('layouts.app', ['title' => 'Buat Surat Baru']);
    }
}
