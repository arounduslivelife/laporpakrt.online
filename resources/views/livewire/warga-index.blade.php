<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Data Warga</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-primary" wire:click="$toggle('showForm')">
                        <i class="bi bi-plus"></i> Tambah Warga
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            @if($showForm)
            <div class="card mb-4">
                <div class="card-header bg-light">{{ $editMode ? 'Edit Data Warga' : 'Input Data Warga Baru' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" wire:model="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status Domisili</label>
                                <select class="form-select" wire:model="status_domisili">
                                    <option value="Tetap">Warga Tetap</option>
                                    <option value="Kontrak">Kontrak/Kos</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>NIK</label>
                                <input type="text" class="form-control" wire:model="nik" maxlength="16">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nomor KK</label>
                                <input type="text" class="form-control" wire:model="no_kk" maxlength="16">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-select" wire:model="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="LAKI-LAKI">Laki-laki</option>
                                    <option value="PEREMPUAN">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tempat, Tanggal Lahir</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" class="form-control" wire:model="tempat_lahir" placeholder="Tempat lahir">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control" wire:model="tanggal_lahir">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" wire:model="alamat" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto Wajah</label>
                                <input type="file" class="form-control" wire:model="foto_wajah">
                                <div wire:loading wire:target="foto_wajah" class="text-info small mt-1">Mengunggah...</div>
                                @if ($foto_wajah)
                                    <img src="{{ $foto_wajah->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @elseif ($editMode && $foto_path)
                                    <img src="{{ Storage::url($foto_path) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto KTP</label>
                                <input type="file" class="form-control" wire:model="foto_ktp" accept="image/*">
                                <div wire:loading wire:target="foto_ktp" class="text-info small mt-1">Mengunggah...</div>
                                @if ($foto_ktp)
                                    <div class="mt-2">
                                        <img src="{{ $foto_ktp->temporaryUrl() }}" class="img-thumbnail" style="max-height: 100px;">
                                        <button type="button" class="btn btn-info btn-sm ms-2" wire:click="scanKtp" wire:loading.attr="disabled" wire:target="scanKtp">
                                            <span wire:loading.remove wire:target="scanKtp"><i class="bi bi-upc-scan"></i> Scan KTP</span>
                                            <span wire:loading wire:target="scanKtp"><i class="bi bi-arrow-repeat spin"></i> Membaca...</span>
                                        </button>
                                    </div>
                                @elseif ($editMode && $ktp_path)
                                    <img src="{{ Storage::url($ktp_path) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @endif
                                @error('foto_ktp') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Batal</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" class="form-control" placeholder="Cari Nama / NIK..." wire:model.live.debounce.300ms="search">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" wire:model.live="filterDomisili">
                                <option value="">Semua Status</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak">Kontrak/Kos</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 Per Halaman</option>
                                <option value="25">25 Per Halaman</option>
                                <option value="50">50 Per Halaman</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 text-end">
                            <a href="{{ route('warga.export.excel') }}" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                            <a href="{{ route('warga.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>NIK</th>
                                <th>No KK</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wargas as $index => $warga)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $warga->name }}</td>
                                <td>{{ $warga->nik ?? '-' }}</td>
                                <td>{{ $warga->no_kk ?? '-' }}</td>
                                <td>{{ $warga->jenis_kelamin ?? '-' }}</td>
                                <td>
                                    @if($warga->status_domisili == 'Tetap')
                                        <span class="badge bg-primary">Tetap</span>
                                    @else
                                        <span class="badge bg-warning">Kontrak</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="edit({{ $warga->id }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="delete({{ $warga->id }})" class="btn btn-sm btn-danger" onclick="confirm('Yakin ingin menghapus warga ini?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Belum ada data warga terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $wargas->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
