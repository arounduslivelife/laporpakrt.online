<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h5>
        <p class="text-muted small">Selamat datang di Panel Warga LaporPakRT</p>
    </div>

    <!-- Info Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="soft-card h-100 mb-0 d-flex flex-column align-items-center justify-content-center text-center p-3" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.5rem;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3 class="fw-bold text-warning-emphasis mb-0">{{ $surats->count() }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">Surat Pengantar</span>
            </div>
        </div>
        <div class="col-6">
            <div class="soft-card h-100 mb-0 d-flex flex-column align-items-center justify-content-center text-center p-3" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.5rem;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $kasTransactions->where('tipe', 'pemasukan')->count() }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">Iuran Dibayar</span>
            </div>
        </div>
    </div>
</div>
