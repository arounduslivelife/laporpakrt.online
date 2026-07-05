<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h5>
        <p class="text-muted small">Selamat bertugas menjaga keamanan RT.</p>
    </div>

    <!-- Info Grid -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="soft-card h-100 mb-0 d-flex flex-column align-items-center justify-content-center text-center p-4" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd);">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h2 class="fw-bold text-primary mb-0">{{ $tamuHariIni }}</h2>
                <span class="text-muted fw-semibold">Tamu Tercatat Hari Ini</span>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('tamu.index') }}" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
            <i class="bi bi-journal-text me-2"></i> Buka Buku Tamu
        </a>
    </div>
</div>
