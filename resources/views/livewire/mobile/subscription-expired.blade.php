<div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
    <div class="mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; background-color: #fee2e2; color: #ef4444;">
            <i class="bi bi-lock-fill" style="font-size: 3rem;"></i>
        </div>
    </div>
    
    <h3 class="fw-bold text-dark mb-3">Akses Terkunci</h3>
    <p class="text-muted mb-4" style="font-size: 0.95rem;">
        Masa langganan RT Anda telah berakhir. Saat ini Anda tidak dapat mengakses fitur-fitur di dalam aplikasi.
    </p>

    <div class="alert alert-warning text-start border-0 w-100 mb-4" style="border-radius: 16px; background-color: #fef3c7; color: #b45309;">
        <div class="d-flex">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 mt-1"></i>
            <span class="small fw-medium">
                Peringatan: Jika tidak diperpanjang maksimal 2 hari setelah jatuh tempo, seluruh data aplikasi RT Anda akan dihapus permanen.
            </span>
        </div>
    </div>

    <a href="mailto:aroundus.livelife@gmail.com" class="btn btn-primary w-100 py-3 fw-bold mb-3" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
        <i class="bi bi-envelope me-1"></i> Hubungi Superadmin
    </a>
    
    <form method="POST" action="{{ route('logout') }}" class="w-100">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 py-3 fw-bold" style="border-radius: 16px;">
            <i class="bi bi-box-arrow-right me-1"></i> Keluar
        </button>
    </form>
</div>
