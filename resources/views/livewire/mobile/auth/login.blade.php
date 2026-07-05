<div class="glass-card text-center">
    <!-- Brand Logo/Title -->
    <div class="mb-4">
        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
            <i class="bi bi-shield-lock text-primary fs-1"></i>
        </div>
        <h1 class="brand-text mb-0">LaporPak<span class="text-primary">RT</span></h1>
        <p class="text-muted mt-2 small">Sistem Informasi Rukun Tetangga</p>
    </div>

    <!-- Login Form -->
    <form wire:submit.prevent="login" class="text-start">
        <div class="mb-4">
            <label class="form-label fw-bold small ms-1 text-muted">Email Anda</label>
            <input type="email" class="form-control form-control-soft" wire:model="email" placeholder="contoh@gmail.com" required>
            @error('email') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold small ms-1 text-muted">Kata Sandi</label>
            <input type="password" class="form-control form-control-soft" wire:model="password" placeholder="••••••••" required>
            @error('password') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-5 ms-1">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="rememberMe" wire:model="remember">
                <label class="form-check-label small fw-medium" for="rememberMe">Ingat Saya</label>
            </div>
            <a href="#" class="text-decoration-none small fw-bold text-primary">Lupa Sandi?</a>
        </div>
        
        <button type="submit" class="btn btn-primary-soft w-100 d-flex justify-content-center align-items-center gap-2">
            <span>Masuk Sekarang</span>
            <i class="bi bi-arrow-right-short fs-4" style="margin: -5px 0;"></i>
        </button>
    </form>

    <div class="mt-5 text-center">
        <p class="small text-muted mb-0">Belum mendaftarkan RT Anda?</p>
        <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-dark">Buat Akun Baru</a>
    </div>
</div>
