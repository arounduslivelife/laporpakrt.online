<div class="login-box" style="width: 500px;">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>LaporPak</b>RT</a>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Daftarkan RT Anda secara gratis</p>

            <form wire:submit.prevent="register">
                <div class="mb-3">
                    <label>Nama Lengkap (Ketua RT/Admin)</label>
                    <input type="text" class="form-control" wire:model="name" placeholder="Nama Lengkap">
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" wire:model="email" placeholder="Email untuk Login">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" wire:model="password">
                    </div>
                    <div class="col-6 mb-3">
                        <label>Ulangi Password</label>
                        <input type="password" class="form-control" wire:model="password_confirmation">
                    </div>
                </div>
                @error('password') <span class="text-danger small d-block mb-2">{{ $message }}</span> @enderror

                <hr>
                <p class="text-muted fw-bold mb-2">Identitas Wilayah RT</p>
                
                <div class="mb-2">
                    <select class="form-select" wire:model.live="province_id">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-2">
                    <select class="form-select" wire:model.live="regency_id" {{ count($regencies) == 0 ? 'disabled' : '' }}>
                        <option value="">Pilih Kota/Kabupaten</option>
                        @foreach($regencies as $reg)
                            <option value="{{ $reg->code }}">{{ $reg->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-2">
                    <select class="form-select" wire:model.live="district_id" {{ count($districts) == 0 ? 'disabled' : '' }}>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($districts as $dis)
                            <option value="{{ $dis->code }}">{{ $dis->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <select class="form-select" wire:model.live="village_id" {{ count($villages) == 0 ? 'disabled' : '' }}>
                        <option value="">Pilih Kelurahan/Desa</option>
                        @foreach($villages as $vil)
                            <option value="{{ $vil->code }}">{{ $vil->name }}</option>
                        @endforeach
                    </select>
                    @error('village_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Nomor RW</label>
                        <input type="number" class="form-control" wire:model="rw" placeholder="Contoh: 01">
                        @error('rw') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label>Nomor RT</label>
                        <input type="number" class="form-control" wire:model="rt" placeholder="Contoh: 04">
                        @error('rt') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3 text-center">
                <a href="{{ route('login') }}" class="text-center">Saya sudah punya akun RT</a>
            </p>
        </div>
    </div>
</div>
