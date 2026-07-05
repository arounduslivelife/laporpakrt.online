<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manajemen Langganan RT</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            @if($showPlanModal)
            <div class="card mb-4 border-primary shadow-sm">
                <div class="card-header text-bg-primary">Edit Paket Langganan</div>
                <div class="card-body">
                    <form wire:submit.prevent="updatePlan">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Nama Paket</label>
                                <input type="text" class="form-control" wire:model="planName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Harga (Rp)</label>
                                <input type="number" class="form-control" wire:model="planPrice" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Durasi (Hari)</label>
                                <input type="number" class="form-control" wire:model="planDuration" required min="1">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" wire:click="$set('showPlanModal', false)">Batal</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="row mb-4">
                @foreach($plans as $plan)
                <div class="col-md-6">
                    <div class="card border-{{ $plan->price == 0 ? 'secondary' : 'primary' }} h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $plan->name }}</h5>
                                <p class="mb-0 text-muted">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_days }} Hari
                                </p>
                            </div>
                            <button class="btn btn-outline-{{ $plan->price == 0 ? 'secondary' : 'primary' }} btn-sm" wire:click="editPlan({{ $plan->id }})">
                                <i class="bi bi-pencil"></i> Edit Harga
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card mb-4 border-info">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Informasi Langganan</h6>
                    <p class="mb-0 text-muted small">
                        Daftar ini memuat status langganan seluruh RT yang terdaftar. Jika status RT <strong>Expired</strong> lebih dari 2 hari, sistem akan menghapus permanen data RT beserta penghuninya setiap tengah malam. Anda dapat memperpanjang masa aktif RT secara manual menggunakan tombol <strong>Perpanjang</strong>.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Langganan RT</h5>
                    <div style="width: 250px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari RT/Desa..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Wilayah RT</th>
                                <th>Paket Langganan</th>
                                <th>Masa Aktif</th>
                                <th>Status</th>
                                <th>Jatuh Tempo (Sisa)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>
                                    <strong>RT {{ $sub->rt->rt }} / RW {{ $sub->rt->rw }}</strong><br>
                                    <small class="text-muted">{{ $sub->rt->village->name ?? '-' }}, Kec. {{ $sub->rt->village->district->name ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($sub->plan->price == 0)
                                        <span class="badge bg-secondary">{{ $sub->plan->name }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ $sub->plan->name }}</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $sub->plan->duration_days }} Hari</small>
                                </td>
                                <td>
                                    <small class="d-block"><i class="bi bi-play-circle text-success me-1"></i> {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</small>
                                    <small class="d-block"><i class="bi bi-stop-circle text-danger me-1"></i> {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    @if(\Carbon\Carbon::parse($sub->end_date)->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($sub->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $endDate = \Carbon\Carbon::parse($sub->end_date);
                                        $diffDays = now()->diffInDays($endDate, false);
                                    @endphp
                                    
                                    @if($diffDays < 0)
                                        <span class="text-danger fw-bold">Lewat {{ abs(intval($diffDays)) }} hari</span>
                                        @if(abs(intval($diffDays)) >= 2)
                                            <br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Terancam dihapus</small>
                                        @endif
                                    @else
                                        <span class="text-success fw-medium">{{ intval($diffDays) }} hari lagi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <button class="btn btn-sm btn-info text-white" wire:click="showRtDetails({{ $sub->rt_id }})">
                                            <i class="bi bi-bar-chart-line me-1"></i> Statistik
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" wire:click="activateSubscription({{ $sub->id }})" onclick="confirm('Perpanjang langganan RT ini dengan paket {{ $sub->plan->name }} selama {{ $sub->plan->duration_days }} hari dari sekarang?') || event.stopImmediatePropagation()">
                                            <i class="bi bi-arrow-repeat me-1"></i> Perpanjang
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data langganan terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail RT -->
    @if($showRtModal && $rtDetails)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-bar-chart-line me-2"></i> Statistik {{ $rtDetails['rt_rw'] }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeRtDetails()"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted text-center mb-4">{{ $rtDetails['village'] }}</p>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100 bg-light">
                                <i class="bi bi-people fs-1 text-primary mb-2"></i>
                                <h4 class="fw-bold mb-0">{{ $rtDetails['total_warga'] }}</h4>
                                <small class="text-muted">Total Warga</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100 bg-light">
                                <i class="bi bi-person-badge fs-1 text-success mb-2"></i>
                                <h4 class="fw-bold mb-0">{{ $rtDetails['total_tamu'] }}</h4>
                                <small class="text-muted">Tamu Keseluruhan<br>({{ $rtDetails['tamu_bulan_ini'] }} bln ini)</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-25 p-2 rounded me-3 text-warning">
                                        <i class="bi bi-wallet2 fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Saldo Kas RT</small>
                                        <span class="fw-bold fs-5">Rp {{ number_format($rtDetails['saldo_kas'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary bg-opacity-10 p-2 rounded me-3 text-secondary">
                                        <i class="bi bi-clock-history fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Terakhir Aktif (Login)</small>
                                        <span class="fw-bold">{{ $rtDetails['last_online'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary w-100" wire:click="closeRtDetails()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

