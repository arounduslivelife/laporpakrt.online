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
                                <input type="file" class="form-control" wire:model="foto_wajah" accept="image/*" capture="environment">
                                <div wire:loading wire:target="foto_wajah" class="text-info small mt-1">Mengunggah...</div>
                                @if ($foto_wajah)
                                    <img src="{{ $foto_wajah->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @elseif ($editMode && $foto_path)
                                    <img src="{{ Storage::url($foto_path) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto KTP (Scan OCR)</label>
                                <!-- Include Cropper.js -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                                
                                <div x-data="{ cropper: null, imageUrl: null, uploading: false }">
                                    <input type="file" id="ktpImageWarga" class="form-control" accept="image/*" capture="environment" 
                                        x-on:change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                imageUrl = URL.createObjectURL(file);
                                                if (cropper) cropper.destroy();
                                                setTimeout(() => {
                                                    const imageElement = document.getElementById('cropperImageWarga');
                                                    cropper = new Cropper(imageElement, {
                                                        viewMode: 1,
                                                        autoCropArea: 0.9,
                                                    });
                                                }, 100);
                                            }
                                        ">
                                    @error('foto_ktp') <span class="text-danger small">{{ $message }}</span> @enderror

                                    <div x-show="imageUrl" class="mt-3" style="width: 100%; max-height: 300px; display:none;">
                                        <img id="cropperImageWarga" :src="imageUrl" style="max-width: 100%;">
                                    </div>

                                    <div x-show="imageUrl" style="display:none;" class="mt-3">
                                        <button type="button" class="btn btn-success btn-sm" x-bind:disabled="uploading"
                                            x-on:click="
                                                uploading = true;
                                                cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob((blob) => {
                                                    if(!blob) { uploading = false; alert('Gagal memotong gambar.'); return; }
                                                    const croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });
                                                    @this.upload('foto_ktp', croppedFile, (uploadedFilename) => {
                                                        uploading = false;
                                                        imageUrl = null;
                                                        if (cropper) cropper.destroy();
                                                        @this.scanKtp();
                                                    }, (error) => { uploading = false; console.error(error); alert('Upload gagal. Cek console.'); }, (event) => {});
                                                }, 'image/jpeg', 0.85);
                                            ">
                                            <span x-show="!uploading"><i class="bi bi-crop"></i> Crop & Scan OCR</span>
                                            <span x-show="uploading"><i class="bi bi-arrow-repeat spin"></i> Memproses...</span>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" x-on:click="imageUrl = null; if(cropper) cropper.destroy(); document.getElementById('ktpImageWarga').value = '';">Batal</button>
                                    </div>
                                    <div wire:loading wire:target="scanKtp" class="text-info mt-2"><i class="bi bi-arrow-repeat spin"></i> Mengekstrak data KTP...</div>
                                    
                                    @if ($foto_ktp && !$editMode)
                                        <div class="mt-2 text-success small" wire:loading.remove wire:target="scanKtp"><i class="bi bi-check-circle"></i> KTP siap disimpan.</div>
                                    @elseif ($editMode && $ktp_path && !$foto_ktp)
                                        <img src="{{ Storage::url($ktp_path) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                    @endif
                                </div>
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($warga->foto_path)
                                            <img src="{{ Storage::url($warga->foto_path) }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white me-2" style="width: 40px; height: 40px;">
                                                {{ substr($warga->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="fw-bold">{{ $warga->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $warga->nik ? substr($warga->nik, 0, 4) . '********' . substr($warga->nik, -4) : '-' }}</td>
                                <td>{{ $warga->no_kk ? substr($warga->no_kk, 0, 4) . '********' . substr($warga->no_kk, -4) : '-' }}</td>
                                <td>{{ $warga->jenis_kelamin ?? '-' }}</td>
                                <td>
                                    @if($warga->status_domisili == 'Tetap')
                                        <span class="badge bg-success"><i class="bi bi-house-check"></i> Tetap</span>
                                    @else
                                        <span class="badge text-bg-warning"><i class="bi bi-house-dash"></i> Kontrak</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="view({{ $warga->id }})" class="btn btn-sm btn-info text-white" title="Lihat Detail"><i class="bi bi-eye"></i></button>
                                    <button wire:click="edit({{ $warga->id }})" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="delete({{ $warga->id }})" class="btn btn-sm btn-danger" onclick="confirm('Yakin ingin menghapus warga ini?') || event.stopImmediatePropagation()" title="Hapus"><i class="bi bi-trash"></i></button>
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

    <!-- View Modal -->
    @if($viewWarga)
    <div class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Warga: {{ $viewWarga->name }}</h5>
                    <button type="button" class="btn-close" wire:click="closeView"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            @if($viewWarga->foto_path)
                                <img src="{{ Storage::url($viewWarga->foto_path) }}" class="img-fluid rounded border shadow-sm" alt="Foto Wajah">
                            @else
                                <div class="bg-secondary rounded d-flex justify-content-center align-items-center text-white mx-auto shadow-sm" style="width: 150px; height: 150px; font-size: 4rem;">
                                    {{ substr($viewWarga->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="mt-3">
                                @if($viewWarga->status_domisili == 'Tetap')
                                    <span class="badge bg-success"><i class="bi bi-house-check"></i> Warga Tetap</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-house-dash"></i> Warga Kontrak</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 35%;">NIK</th>
                                        <td>{{ $viewWarga->nik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. Kartu Keluarga</th>
                                        <td>{{ $viewWarga->no_kk ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <td>{{ $viewWarga->jenis_kelamin ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tempat, Tanggal Lahir</th>
                                        <td>{{ $viewWarga->tempat_lahir ?? '-' }}, {{ $viewWarga->tanggal_lahir ? \Carbon\Carbon::parse($viewWarga->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $viewWarga->alamat ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($viewWarga->ktp_path)
                            <div class="mt-3">
                                <h6>Foto KTP:</h6>
                                <img src="{{ Storage::url($viewWarga->ktp_path) }}" class="img-fluid rounded border shadow-sm" alt="Foto KTP" style="max-height: 200px;">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeView">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
