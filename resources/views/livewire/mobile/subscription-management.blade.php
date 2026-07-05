<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Langganan RT</h5>
        <p class="text-muted small">Status & Jatuh Tempo</p>
    </div>

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
                
                <button class="btn btn-outline-primary w-100 fw-bold" style="border-radius: 12px; font-size: 0.85rem;" wire:click="activateSubscription({{ $sub->id }})" wire:confirm="Perpanjang paket {{ $sub->plan->name }} untuk RT ini?">
                    <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Manual
                </button>
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
</div>
