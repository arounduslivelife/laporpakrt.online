<div class="glass-card text-center my-4" style="max-height: 90vh; overflow-y: auto;">
    <!-- Brand Logo/Title -->
    <div class="mb-4 pt-2">
        <h2 class="brand-text mb-0 fs-3">Daftar LaporPak<span class="text-primary">RT</span></h2>
        <p class="text-muted mt-1 small">Gratis untuk seluruh RT di Indonesia</p>
    </div>

    <!-- Registration Form -->
    <form wire:submit.prevent="register" class="text-start">
        <div class="mb-3">
            <label class="form-label fw-bold small ms-1 text-muted">Nama Lengkap Admin</label>
            <input type="text" class="form-control form-control-soft" wire:model="name" placeholder="Sesuai KTP" required>
            @error('name') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small ms-1 text-muted">Email Aktif</label>
            <input type="email" class="form-control form-control-soft" wire:model="email" placeholder="email@contoh.com" required>
            @error('email') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
        </div>
        
        <div class="row g-2 mb-4">
            <div class="col-6">
                <label class="form-label fw-bold small ms-1 text-muted">Kata Sandi</label>
                <input type="password" class="form-control form-control-soft" wire:model="password" placeholder="Min. 8 Karakter" required>
            </div>
            <div class="col-6">
                <label class="form-label fw-bold small ms-1 text-muted">Ulangi Sandi</label>
                <input type="password" class="form-control form-control-soft" wire:model="password_confirmation" placeholder="Ulangi Sandi" required>
            </div>
            @error('password') <div class="col-12"><span class="text-danger small ms-1">{{ $message }}</span></div> @enderror
        </div>

        <hr class="opacity-25 mb-3">
        <p class="fw-bold small text-primary mb-3"><i class="bi bi-geo-alt-fill me-1"></i> Data Wilayah RT</p>
        
        <div class="mb-3">
            <select class="form-select form-control-soft" wire:model.live="province_id" required>
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <select class="form-select form-control-soft" wire:model.live="regency_id" {{ count($regencies) == 0 ? 'disabled' : '' }} required>
                <option value="">-- Pilih Kota/Kabupaten --</option>
                @foreach($regencies as $reg)
                    <option value="{{ $reg->code }}">{{ $reg->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <select class="form-select form-control-soft" wire:model.live="district_id" {{ count($districts) == 0 ? 'disabled' : '' }} required>
                <option value="">-- Pilih Kecamatan --</option>
                @foreach($districts as $dis)
                    <option value="{{ $dis->code }}">{{ $dis->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <select class="form-select form-control-soft" wire:model.live="village_id" {{ count($villages) == 0 ? 'disabled' : '' }} required>
                <option value="">-- Pilih Kelurahan/Desa --</option>
                @foreach($villages as $vil)
                    <option value="{{ $vil->code }}">{{ $vil->name }}</option>
                @endforeach
            </select>
            @error('village_id') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
        </div>

        <div class="row g-2 mb-4">
            <div class="col-6">
                <label class="form-label fw-bold small ms-1 text-muted">Nomor RW</label>
                <input type="number" class="form-control form-control-soft" wire:model="rw" placeholder="Contoh: 01" required>
                @error('rw') <span class="text-danger small ms-1">{{ $message }}</span> @enderror
            </div>
            <div class="col-6">
                <label class="form-label fw-bold small ms-1 text-muted">Nomor RT</label>
                <input type="number" class="form-control form-control-soft" wire:model="rt" placeholder="Contoh: 04" required>
                @error('rt') <span class="text-danger small ms-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary-soft w-100 d-flex justify-content-center align-items-center gap-2 mt-4">
            <span>Daftar Sekarang</span>
            <i class="bi bi-rocket-takeoff ms-1"></i>
        </button>
    </form>

    <div class="mt-4 pb-2 text-center">
        <p class="small text-muted mb-0">Sudah mendaftarkan RT Anda?</p>
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-dark">Masuk di sini</a>
    </div>
</div>
