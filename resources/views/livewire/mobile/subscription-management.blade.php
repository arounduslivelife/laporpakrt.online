<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Langganan RT</h5>
        <p class="text-muted small mb-3">Status & Jatuh Tempo</p>
        
        <div class="row g-2 mb-2">
            @foreach($plans as $plan)
            <div class="col-6">
                <div class="soft-card p-2 border-{{ $plan->price == 0 ? 'secondary' : 'primary' }}" style="border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; cursor: pointer;" wire:click="editPlan({{ $plan->id }})">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $plan->name }}</span>
                        <i class="bi bi-pencil-square text-muted" style="font-size: 0.7rem;"></i>
                    </div>
                    <span class="text-primary fw-bold d-block" style="font-size: 0.75rem;">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                    <span class="text-muted" style="font-size: 0.65rem;">/ {{ $plan->duration_days }} Hari</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Sheet Form for Edit Plan -->
    @if($showPlanModal)
    <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:click="$set('showPlanModal', false)"></div>
    <div class="position-fixed bottom-0 start-0 w-100 bg-white" style="z-index: 1060; border-radius: 24px 24px 0 0; max-height: 85vh; overflow-y: auto; padding: 24px; transition: transform 0.3s ease-out;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Edit Paket Langganan</h6>
            <button class="btn-close" wire:click="$set('showPlanModal', false)"></button>
        </div>
        
        <form wire:submit.prevent="updatePlan">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Nama Paket</label>
                <input type="text" class="form-control form-control-lg" wire:model="planName" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Harga Langganan (Rp)</label>
                <input type="number" class="form-control form-control-lg" wire:model="planPrice" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required min="0">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted ms-1">Durasi (Hari)</label>
                <input type="number" class="form-control form-control-lg" wire:model="planDuration" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required min="1">
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
                Simpan Perubahan
            </button>
        </form>
    </div>
    @endif

    <div class="alert alert-info alert-dismissible fade show border-0 d-flex align-items-center" role="alert" style="border-radius: 16px; background-color: #e0f2fe; color: #0284c7;">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <span class="small fw-medium">Penghapusan data dilakukan 2 hari setelah masa Expired lewat.</span>
    </div>

    <div class="mb-4">
        <div class="search-box bg-white p-2" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center">
                <i class="bi bi-search text-muted ms-2 me-2"></i>
                <input type="text" class="form-control border-0 shadow-none p-0" placeholder="Cari RT atau Desa..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
    </div>

    <div class="mb-5 pb-4">
        @forelse($subscriptions as $sub)
        @php
            $endDate = \Carbon\Carbon::parse($sub->end_date);
            $diffDays = now()->diffInDays($endDate, false);
            $isExpired = $endDate->isPast();
            
            $bgClass = $isExpired ? '#fee2e2' : ($sub->plan->price > 0 ? '#e0e7ff' : '#f3f4f6');
            $iconColor = $isExpired ? '#ef4444' : ($sub->plan->price > 0 ? '#4f46e5' : '#6b7280');
        @endphp
            <div class="soft-card p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: {{ $bgClass }}; color: {{ $iconColor }};">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">RT {{ $sub->rt->rt }} / RW {{ $sub->rt->rw }}</h6>
                            <span class="text-muted d-block" style="font-size: 0.75rem;">{{ $sub->rt->village->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        @if($isExpired)
                            <span class="badge bg-danger rounded-pill">Expired</span>
                        @else
                            <span class="badge bg-success rounded-pill">Aktif</span>
                        @endif
                    </div>
                </div>

                <div class="bg-light p-2 rounded-3 mb-3 d-flex justify-content-between" style="font-size: 0.8rem;">
                    <div>
                        <span class="text-muted d-block">Paket</span>
                        <span class="fw-bold text-dark">{{ $sub->plan->name }}</span>
                    </div>
                    <div class="text-end">
                        <span class="text-muted d-block">Jatuh Tempo</span>
                        @if($diffDays < 0)
                            <span class="fw-bold text-danger">Lewat {{ abs(intval($diffDays)) }} hari</span>
                        @else
                            <span class="fw-bold text-success">{{ intval($diffDays) }} hari lagi</span>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-info text-white fw-bold flex-fill" style="border-radius: 12px; font-size: 0.85rem;" wire:click="showRtDetails({{ $sub->rt_id }})">
                        <i class="bi bi-bar-chart-line me-1"></i> Statistik
                    </button>
                    <button class="btn btn-outline-primary fw-bold flex-fill" style="border-radius: 12px; font-size: 0.85rem;" wire:click="activateSubscription({{ $sub->id }})" wire:confirm="Perpanjang paket {{ $sub->plan->name }} untuk RT ini?">
                        <i class="bi bi-arrow-repeat me-1"></i> Perpanjang
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-shield-x text-primary fs-2"></i>
                </div>
                <p class="text-muted fw-medium">Belum ada RT yang berlangganan</p>
            </div>
        @endforelse
    </div>

    <!-- Bottom Sheet Form for RT Details -->
    @if($showRtModal && $rtDetails)
    <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:click="closeRtDetails()"></div>
    <div class="position-fixed bottom-0 start-0 w-100 bg-white" style="z-index: 1060; border-radius: 24px 24px 0 0; max-height: 85vh; overflow-y: auto; padding: 24px; transition: transform 0.3s ease-out;">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h6 class="fw-bold mb-0 text-info"><i class="bi bi-bar-chart-line me-1"></i> Statistik {{ $rtDetails['rt_rw'] }}</h6>
            <button class="btn-close" wire:click="closeRtDetails()"></button>
        </div>
        <p class="text-muted small mb-4">{{ $rtDetails['village'] }}</p>
        
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="p-3 text-center rounded-4 h-100" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
                    <i class="bi bi-people fs-2 text-success mb-1"></i>
                    <h4 class="fw-bold text-dark mb-0">{{ $rtDetails['total_warga'] }}</h4>
                    <small class="text-muted" style="font-size: 0.7rem;">Warga</small>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 text-center rounded-4 h-100" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                    <i class="bi bi-person-badge fs-2 text-primary mb-1"></i>
                    <h4 class="fw-bold text-dark mb-0">{{ $rtDetails['total_tamu'] }}</h4>
                    <small class="text-muted" style="font-size: 0.7rem;">Tamu ({{ $rtDetails['tamu_bulan_ini'] }} bln ini)</small>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex align-items-center p-3 rounded-4" style="background-color: #fefce8; border: 1px solid #fef08a;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #facc15; color: white;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div>
                    <span class="d-block text-muted" style="font-size: 0.75rem;">Saldo Kas RT</span>
                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($rtDetails['saldo_kas'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex align-items-center p-3 rounded-4 bg-light" style="border: 1px solid #e5e7eb;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 bg-secondary text-white" style="width: 40px; height: 40px;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <span class="d-block text-muted" style="font-size: 0.75rem;">Terakhir Aktif</span>
                    <span class="fw-bold text-dark fs-6">{{ $rtDetails['last_online'] }}</span>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary w-100 py-3 fw-bold mb-4" style="border-radius: 16px;" wire:click="closeRtDetails()">
            Tutup
        </button>
    </div>
    @endif
</div>

