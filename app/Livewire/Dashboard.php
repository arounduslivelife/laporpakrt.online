<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Warga;
use App\Models\Tamu;
use App\Models\KasTransaction;
use App\Models\Surat;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $role = auth()->user()->role;
        
        if ($role == 'warga') {
            return $this->renderWarga();
        } elseif ($role == 'security') {
            return $this->renderSecurity();
        }

        $rtId = auth()->user()->rt_id;
        $rtInfo = auth()->user()->rt;
        
        $totalWarga = Warga::where('rt_id', $rtId)->count();
        $tamuBulanIni = Tamu::where('rt_id', $rtId)->whereMonth('created_at', date('m'))->count();
        
        $pemasukan = KasTransaction::where('rt_id', $rtId)->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = KasTransaction::where('rt_id', $rtId)->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoKas = $pemasukan - $pengeluaran;

        $chartData = KasTransaction::where('rt_id', $rtId)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw("SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan"),
                DB::raw("SUM(CASE WHEN tipe = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartBulan = $chartData->pluck('bulan')->toArray();
        $chartPemasukan = $chartData->pluck('pemasukan')->toArray();
        $chartPengeluaran = $chartData->pluck('pengeluaran')->toArray();

        return view('livewire.dashboard', compact('totalWarga', 'tamuBulanIni', 'saldoKas', 'chartBulan', 'chartPemasukan', 'chartPengeluaran', 'rtInfo'))
            ->layout('layouts.app', ['title' => 'Dashboard RT']);
    }

    public function renderWarga()
    {
        $wargaId = auth()->user()->warga_id;
        $kasTransactions = KasTransaction::where('warga_id', $wargaId)->latest()->get();
        $surats = Surat::where('warga_id', $wargaId)->latest()->get();
        return view('livewire.dashboard-warga', compact('kasTransactions', 'surats'))
            ->layout('layouts.app', ['title' => 'Beranda Warga']);
    }

    public function renderSecurity()
    {
        $rtId = auth()->user()->rt_id;
        $tamuHariIni = Tamu::where('rt_id', $rtId)->whereDate('created_at', date('Y-m-d'))->count();
        return view('livewire.dashboard-security', compact('tamuHariIni'))
            ->layout('layouts.app', ['title' => 'Dashboard Security']);
    }
}
