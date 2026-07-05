<div>
    {{-- Assets - SELALU di-load, di luar conditional --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .ktp-upload-box {
            border: 2.5px dashed #0d6efd; border-radius: 1rem; padding: 2rem 1rem;
            text-align: center; cursor: pointer; transition: all .3s ease;
            background: linear-gradient(135deg, #f0f5ff 0%, #e0ecff 100%);
        }
        .ktp-upload-box:hover { background: linear-gradient(135deg, #dce8ff 0%, #c5d8ff 100%); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13,110,253,.15); }
        .ktp-upload-box .upload-icon { font-size: 2.5rem; color: #0d6efd; }
        .crop-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.92); z-index: 9999; display: none; flex-direction: column; }
        .crop-header { padding: 1rem 1.5rem; background: #1e1e2e; color: #fff; display: flex; align-items: center; justify-content: space-between; }
        .crop-body { flex: 1; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #111; }
        .crop-body img { max-width: 100%; max-height: 100%; }
        .crop-footer { padding: 1rem 1.5rem; background: #1e1e2e; display: flex; gap: .75rem; justify-content: center; }
        .ocr-success-box { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border: 2px solid #66bb6a; border-radius: 1rem; padding: 1.5rem; text-align: center; }
        .tip-box { background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: .75rem; padding: 1rem 1.25rem; border-left: 4px solid #1976d2; }
        .scan-shimmer { height: 4px; border-radius: 2px; overflow: hidden; background: #e0e0e0; }
        .scan-shimmer-bar { height: 100%; width: 100%; background: linear-gradient(90deg, #1976d2, #42a5f5, #1976d2); background-size: 200%; animation: shimmer 1.2s infinite linear; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .spin { animation: spin-anim 1s linear infinite; }
        @keyframes spin-anim { from{transform:rotate(0)} to{transform:rotate(360deg)} }
    </style>

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
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-person-plus me-2 text-primary"></i>
                            {{ $editMode ? 'Edit Data Warga' : 'Input Data Warga Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="resetForm"></button>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form wire:submit.prevent="save">

                        {{-- KTP OCR Section --}}
                        <div class="mb-4" wire:ignore>
                            <div class="tip-box mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-magic text-primary me-2" style="font-size:1.3rem; margin-top:2px;"></i>
                                    <div>
                                        <strong>Isi Otomatis dengan Scan KTP</strong><br>
                                        <small class="text-muted">Foto KTP lalu crop area-nya. Data NIK, Nama, Alamat, dll akan terisi otomatis.</small>
                                    </div>
                                </div>
                            </div>

                            <div id="ktp-dropzone-area">
                                <div class="ktp-upload-box" onclick="document.getElementById('ktpFileInput').click()">
                                    <div class="upload-icon"><i class="bi bi-credit-card-2-front"></i></div>
                                    <h6 class="fw-bold mt-2 mb-1">Klik untuk Foto / Upload KTP</h6>
                                    <small class="text-muted">Kamera akan terbuka di HP &bull; Maks 8MB</small>
                                </div>
                                <input type="file" id="ktpFileInput" accept="image/*" capture="environment" class="d-none"
                                    onchange="window._wargaOpenCropper(this)">
                            </div>

                            <div id="ktp-success-area" style="display:none;">
                                <div class="ocr-success-box">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:2rem;"></i>
                                    <h6 class="fw-bold mt-2 mb-1 text-success">KTP Berhasil Di-scan!</h6>
                                    <small class="text-muted">Periksa data yang sudah terisi di bawah</small>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window._wargaResetKtp()">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Scan Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div wire:loading wire:target="scanKtp" class="mb-3">
                            <div class="d-flex align-items-center gap-2 text-primary mb-2">
                                <i class="bi bi-cpu spin"></i>
                                <strong class="small">AI sedang membaca data KTP...</strong>
                            </div>
                            <div class="scan-shimmer"><div class="scan-shimmer-bar"></div></div>
                        </div>
                        @error('foto_ktp') <div class="text-danger small mb-3"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror

                        <hr>

                        <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Data Identitas</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" required placeholder="Sesuai KTP">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">NIK</label>
                                <input type="text" class="form-control" wire:model="nik" maxlength="16" placeholder="16 digit">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor KK</label>
                                <input type="text" class="form-control" wire:model="no_kk" maxlength="16" placeholder="16 digit">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                <select class="form-select" wire:model="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="LAKI-LAKI">Laki-laki</option>
                                    <option value="PEREMPUAN">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status Domisili</label>
                                <select class="form-select" wire:model="status_domisili">
                                    <option value="Tetap">Warga Tetap</option>
                                    <option value="Kontrak">Kontrak/Kos</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <input type="text" class="form-control" wire:model="tempat_lahir" placeholder="Kota">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input type="date" class="form-control" wire:model="tanggal_lahir">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control" wire:model="alamat" rows="2" placeholder="Alamat lengkap sesuai KTP"></textarea>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-camera me-1"></i> Foto Wajah (Opsional)</h6>
                        <div class="mb-3">
                            <input type="file" class="form-control" wire:model="foto_wajah" accept="image/*" capture="environment">
                            <div wire:loading wire:target="foto_wajah" class="text-info small mt-1"><i class="bi bi-arrow-repeat spin"></i> Mengunggah...</div>
                            @if ($foto_wajah)
                                <img src="{{ $foto_wajah->temporaryUrl() }}" class="img-thumbnail mt-2 rounded" style="max-height: 100px;">
                            @elseif ($editMode && $foto_path)
                                <img src="{{ Storage::url($foto_path) }}" class="img-thumbnail mt-2 rounded" style="max-height: 100px;">
                            @endif
                        </div>

                        <hr>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Simpan Data
                            </button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Batal</button>
                        </div>
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
                                    <tr><th style="width: 35%;">NIK</th><td>{{ $viewWarga->nik ?? '-' }}</td></tr>
                                    <tr><th>No. Kartu Keluarga</th><td>{{ $viewWarga->no_kk ?? '-' }}</td></tr>
                                    <tr><th>Jenis Kelamin</th><td>{{ $viewWarga->jenis_kelamin ?? '-' }}</td></tr>
                                    <tr><th>Tempat, Tanggal Lahir</th><td>{{ $viewWarga->tempat_lahir ?? '-' }}, {{ $viewWarga->tanggal_lahir ? \Carbon\Carbon::parse($viewWarga->tanggal_lahir)->format('d-m-Y') : '-' }}</td></tr>
                                    <tr><th>Alamat</th><td>{{ $viewWarga->alamat ?? '-' }}</td></tr>
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

    {{-- ============================================================ --}}
    {{-- Cropper overlay & Scripts - DI LUAR @if($showForm) --}}
    {{-- Agar selalu di-load saat halaman pertama kali dibuka --}}
    {{-- ============================================================ --}}
    <div id="wargaCropOverlay" class="crop-overlay">
        <div class="crop-header">
            <h6 class="mb-0"><i class="bi bi-crop me-2"></i>Potong Area KTP</h6>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window._wargaCloseCropper()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-2 text-center" style="background:#2a2a3e; color:#adb5bd;">
            <small><i class="bi bi-info-circle me-1"></i>Seret kotak biru agar membingkai KTP saja. Buang latar belakang.</small>
        </div>
        <div class="crop-body">
            <img id="wargaCropImage" src="" alt="KTP">
        </div>
        <div class="crop-footer">
            <button type="button" class="btn btn-outline-light" onclick="window._wargaCloseCropper()">Batal</button>
            <button type="button" class="btn btn-primary px-4" id="btnWargaCropScan" onclick="window._wargaDoCrop()">
                <i class="bi bi-upc-scan me-1"></i> Crop & Scan
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        (function() {
            var cropper = null;

            window._wargaOpenCropper = function(input) {
                var file = input.files[0];
                if (!file) return;
                if (file.size > 8 * 1024 * 1024) { alert('File terlalu besar. Maks 8MB.'); input.value = ''; return; }
                if (!file.type.startsWith('image/')) { alert('Hanya file gambar.'); input.value = ''; return; }

                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('wargaCropImage');
                    img.src = e.target.result;
                    document.getElementById('wargaCropOverlay').style.display = 'flex';

                    setTimeout(function() {
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(img, {
                            viewMode: 1,
                            autoCropArea: 0.85,
                            responsive: true,
                            background: true,
                        });
                    }, 300);
                };
                reader.readAsDataURL(file);
            };

            window._wargaCloseCropper = function() {
                document.getElementById('wargaCropOverlay').style.display = 'none';
                if (cropper) { cropper.destroy(); cropper = null; }
            };

            window._wargaDoCrop = function() {
                if (!cropper) return;
                var btn = document.getElementById('btnWargaCropScan');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Memproses...';

                cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob(function(blob) {
                    if (!blob) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan';
                        alert('Gagal crop gambar.');
                        return;
                    }
                    var croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });

                    var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                    component.upload('foto_ktp', croppedFile, function() {
                        window._wargaCloseCropper();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan';

                        var dropzone = document.getElementById('ktp-dropzone-area');
                        var success = document.getElementById('ktp-success-area');
                        if (dropzone) dropzone.style.display = 'none';
                        if (success) success.style.display = 'block';

                        component.call('scanKtp');
                    }, function(error) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan';
                        console.error('Upload error:', error);
                        alert('Upload gagal. Cek koneksi internet.');
                    }, function() {});
                }, 'image/jpeg', 0.85);
            };

            window._wargaResetKtp = function() {
                var dropzone = document.getElementById('ktp-dropzone-area');
                var success = document.getElementById('ktp-success-area');
                if (dropzone) dropzone.style.display = 'block';
                if (success) success.style.display = 'none';
                var input = document.getElementById('ktpFileInput');
                if (input) input.value = '';
            };
        })();
    </script>
</div>
