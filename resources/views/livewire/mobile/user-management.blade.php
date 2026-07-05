<div>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1">Manajemen Akses</h5>
            <p class="text-muted small mb-0">Akun Warga & Satpam</p>
        </div>
    </div>

    <div class="mb-4">
        <div class="search-box bg-white p-2" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center">
                <i class="bi bi-search text-muted ms-2 me-2"></i>
                <input type="text" class="form-control border-0 shadow-none p-0" placeholder="Cari nama, email, atau RT..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="mb-5 pb-4">
        @forelse($users as $user)
            <div class="soft-card p-3 mb-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; 
                        background: {{ $user->role == 'rt_admin' ? '#dbeafe' : ($user->role == 'security' ? '#f3f4f6' : ($user->role == 'superadmin' ? '#e2e8f0' : '#dcfce7')) }};
                        color: {{ $user->role == 'rt_admin' ? '#2563eb' : ($user->role == 'security' ? '#4b5563' : ($user->role == 'superadmin' ? '#0f172a' : '#16a34a')) }};">
                        <i class="bi bi-person{{ $user->role == 'security' ? '-badge' : '' }} fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">{{ $user->name }}</h6>
                        <span class="text-muted d-block mb-1" style="font-size: 0.75rem;">{{ $user->email }}</span>
                        @if($user->rt)
                        <span class="text-muted d-block mb-1" style="font-size: 0.70rem;"><i class="bi bi-geo-alt me-1"></i>RT {{ $user->rt->rt }}/RW {{ $user->rt->rw }}</span>
                        @endif
                        <span class="badge {{ $user->role == 'rt_admin' ? 'bg-primary' : ($user->role == 'security' ? 'bg-secondary' : ($user->role == 'superadmin' ? 'bg-dark' : 'bg-success')) }} bg-opacity-10 text-{{ $user->role == 'rt_admin' ? 'primary' : ($user->role == 'security' ? 'secondary' : ($user->role == 'superadmin' ? 'dark' : 'success')) }} rounded-pill" style="font-size: 0.65rem;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div class="text-end">
                    @if(auth()->user()->id != $user->id)
                    <button class="btn btn-outline-danger btn-sm rounded-circle" style="width: 36px; height: 36px;" wire:click="delete({{ $user->id }})" wire:confirm="Hapus akun ini secara permanen?">
                        <i class="bi bi-trash"></i>
                    </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-people text-primary fs-2"></i>
                </div>
                <p class="text-muted fw-medium">Belum ada akun terdaftar</p>
            </div>
        @endforelse
    </div>

    <!-- FAB -->
    <button class="fab" wire:click="$toggle('showForm')">
        <i class="bi bi-person-plus"></i>
    </button>

    <!-- Bottom Sheet Form for Add User -->
    @if($showForm)
    <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:click="$set('showForm', false)"></div>
    <div class="position-fixed bottom-0 start-0 w-100 bg-white" style="z-index: 1060; border-radius: 24px 24px 0 0; max-height: 85vh; overflow-y: auto; padding: 24px; transition: transform 0.3s ease-out;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Buat Akun Baru</h6>
            <button class="btn-close" wire:click="$set('showForm', false)"></button>
        </div>
        
        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Peran Akses</label>
                <select class="form-select form-select-lg" wire:model.live="role" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                    <option value="warga">Warga Biasa</option>
                    <option value="security">Satpam / Keamanan</option>
                </select>
            </div>
            
            @if($role == 'warga')
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Pilih Data Warga</label>
                <select class="form-select form-select-lg" wire:model="warga_id" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required>
                    <option value="">-- Warga Terdaftar --</option>
                    @foreach($wargas as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ substr($w->nik, 0, 6) }}...)</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Nama Satpam</label>
                <input type="text" class="form-control form-control-lg" wire:model="name" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required placeholder="Nama Lengkap">
            </div>
            @endif
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted ms-1">Email Login</label>
                <input type="email" class="form-control form-control-lg" wire:model="email" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required placeholder="email@contoh.com">
                @error('email') <span class="text-danger small mt-1 ms-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted ms-1">Password</label>
                <input type="text" class="form-control form-control-lg" wire:model="password" style="border-radius: 12px; font-size: 0.95rem; border: 1px solid #e2e8f0; background-color: #f8fafc;" required minlength="6" placeholder="Minimal 6 karakter">
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
                Simpan & Buat Akun
            </button>
        </form>
    </div>
    @endif
</div>
