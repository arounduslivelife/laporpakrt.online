<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manajemen Akses (Warga & Satpam)</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-primary" wire:click="$toggle('showForm')"><i class="bi bi-person-plus"></i> Buat Akun Baru</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            @if($showForm)
            <div class="card mb-4 border-primary">
                <div class="card-header text-bg-primary">Buat Akun Akses Baru</div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Peran (Role)</label>
                                <select class="form-select" wire:model.live="role" required>
                                    <option value="warga">Warga</option>
                                    <option value="security">Satpam / Security</option>
                                </select>
                            </div>
                            
                            @if($role == 'warga')
                            <div class="col-md-6 mb-3">
                                <label>Pilih Warga</label>
                                <select class="form-select" wire:model="warga_id" required>
                                    <option value="">-- Pilih Warga Terdaftar --</option>
                                    @foreach($wargas as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }} (NIK: {{ $w->nik }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Nama akun akan disesuaikan dengan data warga.</small>
                            </div>
                            @else
                            <div class="col-md-6 mb-3">
                                <label>Nama Lengkap Satpam</label>
                                <input type="text" class="form-control" wire:model="name" required>
                            </div>
                            @endif
                            
                            <div class="col-md-6 mb-3">
                                <label>Email Login</label>
                                <input type="email" class="form-control" wire:model="email" required placeholder="email@contoh.com">
                                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Password Login</label>
                                <input type="text" class="form-control" wire:model="password" required minlength="6">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan & Buat Akun</button>
                        <button type="button" class="btn btn-secondary" wire:click="$toggle('showForm')">Batal</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Terhubung Dengan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'warga')
                                        <span class="badge bg-info">Warga</span>
                                    @elseif($user->role == 'security')
                                        <span class="badge bg-secondary">Satpam</span>
                                    @else
                                        <span class="badge bg-primary">Admin RT</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role == 'warga' && $user->warga_id)
                                        <a href="#" class="text-decoration-none">Data Warga ID #{{ $user->warga_id }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $user->id }})" onclick="return confirm('Hapus akun ini? Akun tidak akan bisa login lagi.')"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada akun tambahan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
