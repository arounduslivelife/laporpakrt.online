<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>LaporPak</b>RT</a>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Masuk untuk mengelola RT Anda</p>

            <form wire:submit.prevent="login">
                <div class="mb-3">
                    <input type="email" class="form-control" wire:model="email" placeholder="Email">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" wire:model="password" placeholder="Password">
                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block w-100">Masuk</button>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3">
                <a href="{{ route('register') }}" class="text-center">Belum mendaftarkan RT? Daftar Baru</a>
            </p>
        </div>
    </div>
</div>
