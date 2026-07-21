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
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" wire:model="password" placeholder="Password" id="passwordInput" style="padding-right: 40px;">
                    <button type="button" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted p-2" onclick="togglePassword('passwordInput', 'eyeIconLogin')" style="z-index: 10;">
                        <i class="bi bi-eye-slash" id="eyeIconLogin"></i>
                    </button>
                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
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

<script>
    function togglePassword(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
