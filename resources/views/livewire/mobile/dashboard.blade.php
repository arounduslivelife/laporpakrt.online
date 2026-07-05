<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h5>
        <p class="text-muted small">Selamat datang di Panel RT {{ $rtInfo->rt }} / RW {{ $rtInfo->rw }}</p>
    </div>

    <!-- Info Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="soft-card h-100 mb-0 d-flex flex-column align-items-center justify-content-center text-center p-3" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd);">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.5rem;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3 class="fw-bold text-primary mb-0">{{ $totalWarga }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">Total Warga</span>
            </div>
        </div>
        <div class="col-6">
            <div class="soft-card h-100 mb-0 d-flex flex-column align-items-center justify-content-center text-center p-3" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.5rem;">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <h3 class="fw-bold text-warning-emphasis mb-0">{{ $tamuBulanIni }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">Tamu Bulan Ini</span>
            </div>
        </div>
        <div class="col-12">
            <div class="soft-card h-100 mb-0 d-flex align-items-center p-3" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <span class="text-success fw-semibold" style="font-size: 0.8rem;">Saldo Kas RT Saat Ini</span>
                    <h3 class="fw-bold text-success mb-0" style="letter-spacing: -0.5px;">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h6 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">Akses Cepat</h6>
    <div class="row g-3 mb-5">
        <div class="col-4">
            <a href="{{ route('warga.index') }}" class="text-decoration-none">
                <div class="soft-card text-center p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-person-plus text-primary fs-2 mb-1"></i>
                    <span class="text-dark fw-semibold" style="font-size: 0.75rem;">Warga Baru</span>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('tamu.index') }}" class="text-decoration-none">
                <div class="soft-card text-center p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-book text-success fs-2 mb-1"></i>
                    <span class="text-dark fw-semibold" style="font-size: 0.75rem;">Buku Tamu</span>
                </div>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('kas.index') }}" class="text-decoration-none">
                <div class="soft-card text-center p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-cash-coin text-warning fs-2 mb-1"></i>
                    <span class="text-dark fw-semibold" style="font-size: 0.75rem;">Kas RT</span>
                </div>
            </a>
        </div>
    </div>
</div>
