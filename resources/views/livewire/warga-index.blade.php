<div>
    {{-- Shared Assets --}}
    <style>
        .ktp-upload-box {
            border: 2.5px dashed #0d6efd; border-radius: 1rem; padding: 2rem 1rem;
            text-align: center; cursor: pointer; transition: all .3s ease;
            background: linear-gradient(135deg, #f0f5ff 0%, #e0ecff 100%);
        }
        .ktp-upload-box:hover { background: linear-gradient(135deg, #dce8ff 0%, #c5d8ff 100%); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13,110,253,.15); }
        .ktp-upload-box .upload-icon { font-size: 2.5rem; color: #0d6efd; }
        
        .ocr-success-box { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border: 2px solid #66bb6a; border-radius: 1rem; padding: 1.5rem; text-align: center; }
        .tip-box { background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: .75rem; padding: 1rem 1.25rem; border-left: 4px solid #1976d2; }
        
        .scan-shimmer { height: 4px; border-radius: 2px; overflow: hidden; background: #e0e0e0; }
        .scan-shimmer-bar { height: 100%; width: 100%; background: linear-gradient(90deg, #1976d2, #42a5f5, #1976d2); background-size: 200%; animation: shimmer 1.2s infinite linear; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .spin { animation: spin-anim 1s linear infinite; }
        @keyframes spin-anim { from{transform:rotate(0)} to{transform:rotate(360deg)} }

        /* Full Screen Scanner Overlay */
        .scanner-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.95);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(8px);
        }
        .scan-box {
            width: 280px; height: 180px;
            border: 2px solid rgba(59, 130, 246, 0.5);
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        .scan-line-anim {
            width: 100%; height: 3px;
            background: #3b82f6;
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
            position: absolute;
            top: 0;
            animation: scan 2s infinite ease-in-out;
        }
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <h3 class="mb-0">Data Warga</h3>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-primary d-none d-md-inline-block" wire:click="$toggle('showForm')">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Warga
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

                        {{-- KTP Scanner Section --}}
                        <div class="mb-4" wire:ignore>
                            <div class="tip-box mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-magic text-primary me-2" style="font-size:1.3rem; margin-top:2px;"></i>
                                    <div>
                                        <strong>Scan KTP Cerdas (In-App)</strong><br>
                                        <small class="text-muted">Arahkan kamera ke KTP, posisikan ke dalam kotak panduan, dan data akan otomatis terisi.</small>
                                    </div>
                                </div>
                            </div>

                            <div id="ktp-dropzone-area">
                                <div class="ktp-upload-box" onclick="document.getElementById('ktpFileInput').click()">
                                    <div class="upload-icon"><i class="bi bi-camera"></i></div>
                                    <h6 class="fw-bold mt-2 mb-1">Klik untuk Ambil Foto KTP</h6>
                                    <small class="text-muted">Kamera bawaan HP akan terbuka untuk hasil lebih tajam</small>
                                </div>
                                <input type="file" id="ktpFileInput" accept="image/*" capture="environment" class="d-none"
                                    onchange="window._wargaProcessKtp(this)">
                            </div>

                            <div id="ktp-success-area" style="display:none;">
                                <div class="ocr-success-box">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:2rem;"></i>
                                    <h6 class="fw-bold mt-2 mb-1 text-success">KTP Berhasil Difoto!</h6>
                                    <div class="mt-3">
                                        <img id="ktp-preview-img" src="" class="img-thumbnail rounded mb-2" style="max-height: 120px; display: none; margin: 0 auto;">
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window._wargaResetKtp()">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Foto Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div wire:loading wire:target="scanKtp">
                            <div class="scanner-overlay">
                                <div class="scan-box mb-4">
                                    <div class="scan-line-anim"></div>
                                </div>
                                <h4 class="text-white fw-bold">Sedang Menganalisis KTP...</h4>
                                <p class="text-light opacity-75">AI sedang mengekstrak data identitas</p>
                            </div>
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

            <!-- Duplicate NIK Warning Modal -->
            @if($showDuplicateWarning)
            <div class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Peringatan Duplikasi NIK</h5>
                            <button type="button" class="btn-close" wire:click="$set('showDuplicateWarning', false)"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-person-exclamation text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">NIK Sudah Terdaftar!</h5>
                            <p class="mb-1">Sistem mendeteksi NIK <strong>{{ $nik }}</strong> sudah terdaftar atas nama:</p>
                            <h6 class="fw-bold text-primary">{{ $duplicateInfo['name'] ?? '-' }}</h6>
                            <p class="mb-3">di wilayah <strong>{{ $duplicateInfo['rt'] ?? '-' }}</strong>.</p>
                            
                            <div class="alert alert-light border small text-start">
                                <i class="bi bi-info-circle me-1"></i> Data mungkin belum diperbarui oleh RT asal. Jika Anda yakin ini adalah warga Anda (misal baru pindah), Anda dapat melanjutkan.
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center bg-light">
                            <button type="button" class="btn btn-secondary px-4" wire:click="$set('showDuplicateWarning', false)">Batal</button>
                            <button type="button" class="btn btn-warning px-4 fw-bold" wire:click="confirmSave">
                                <i class="bi bi-check-circle me-1"></i> Tetap Tambahkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Cari Nama / NIK..." wire:model.live.debounce.300ms="search">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterDomisili">
                                <option value="">Semua Status</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak">Kontrak/Kos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 Per Halaman</option>
                                <option value="25">25 Per Halaman</option>
                                <option value="50">50 Per Halaman</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end d-flex gap-1 justify-content-md-end">
                            <a href="{{ route('warga.export.excel') }}" class="btn btn-success btn-sm flex-fill flex-md-grow-0"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                            <a href="{{ route('warga.export.pdf') }}" class="btn btn-danger btn-sm flex-fill flex-md-grow-0" target="_blank"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
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

    <script>
        (function() {
            window._wargaProcessKtp = function(input) {
                var file = input.files[0];
                if (!file) return;
                
                // Tampilkan loading di UI
                var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                
                var dropzone = document.getElementById('ktp-dropzone-area');
                var success = document.getElementById('ktp-success-area');
                var imgPreview = document.getElementById('ktp-preview-img');
                
                if (dropzone) dropzone.style.display = 'none';
                if (success) success.style.display = 'block';
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        var maxDim = 1600;
                        var w = img.width;
                        var h = img.height;
                        
                        if (w > maxDim || h > maxDim) {
                            if (w > h) { h = Math.round((h * maxDim) / w); w = maxDim; }
                            else { w = Math.round((w * maxDim) / h); h = maxDim; }
                        }
                        
                        canvas.width = w; canvas.height = h;
                        ctx.drawImage(img, 0, 0, w, h);
                        
                        canvas.toBlob(function(blob) {
                            if (!blob) { alert('Gagal memproses gambar'); window._wargaResetKtp(); return; }
                            
                            var previewUrl = URL.createObjectURL(blob);
                            if (imgPreview) { imgPreview.src = previewUrl; imgPreview.style.display = 'block'; }
                            
                            var compressedFile = new File([blob], 'ktp.jpg', { type: 'image/jpeg' });
                            
                            component.upload('foto_ktp', compressedFile, function() {
                                component.call('scanKtp');
                            }, function(error) {
                                alert('Gagal mengupload KTP. Periksa koneksi internet Anda.');
                                window._wargaResetKtp();
                            }, function() {});
                            
                        }, 'image/jpeg', 0.85);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            };

            window._wargaResetKtp = function() {
                var dropzone = document.getElementById('ktp-dropzone-area');
                var success = document.getElementById('ktp-success-area');
                var imgPreview = document.getElementById('ktp-preview-img');
                var input = document.getElementById('ktpFileInput');
                
                if (dropzone) dropzone.style.display = 'block';
                if (success) success.style.display = 'none';
                if (imgPreview) { imgPreview.src = ''; imgPreview.style.display = 'none'; }
                if (input) input.value = '';
            };
        })();
    </script>
</div>
