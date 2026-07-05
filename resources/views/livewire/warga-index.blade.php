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

        /* === WEBCAM OVERLAY === */
        .webcam-overlay{position:fixed;inset:0;background:#000;z-index:9999;display:none;flex-direction:column}
        .webcam-header{padding:1rem;background:#1e1e2e;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .webcam-body{flex:1;position:relative;overflow:hidden;background:#000;display:flex;align-items:center;justify-content:center}
        .webcam-body video{min-width:100%;min-height:100%;object-fit:cover;position:absolute;inset:0;z-index:1}
        .ktp-guide{position:absolute;width:90%;aspect-ratio:1.58;border:2px solid rgba(255,255,255,0.7);box-shadow:0 0 0 3000px rgba(0,0,0,0.6);z-index:10;border-radius:8px}
        @media (min-aspect-ratio: 1/1) { .ktp-guide { width: 50%; } }
        .guide-corner{position:absolute;width:30px;height:30px;border-color:#0d6efd;border-style:solid;z-index:11}
        .guide-corner.top-left{top:-2px;left:-2px;border-width:4px 0 0 4px;border-top-left-radius:8px}
        .guide-corner.top-right{top:-2px;right:-2px;border-width:4px 4px 0 0;border-top-right-radius:8px}
        .guide-corner.bottom-left{bottom:-2px;left:-2px;border-width:0 0 4px 4px;border-bottom-left-radius:8px}
        .guide-corner.bottom-right{bottom:-2px;right:-2px;border-width:0 4px 4px 0;border-bottom-right-radius:8px}
        .guide-text{position:absolute;top:-30px;left:0;right:0;text-align:center;color:#fff;font-size:14px;font-weight:600;text-shadow:1px 1px 3px #000}
        .webcam-footer{padding:1.5rem 1rem;background:#1e1e2e;display:flex;gap:1rem;justify-content:center;flex-shrink:0;z-index:10}
        .webcam-footer .btn{min-width:130px;padding:.8rem 1.5rem;font-weight:600;border-radius:2rem;font-size:1rem}
    </style>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <h3 class="mb-0">Data Warga</h3>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-primary" wire:click="$toggle('showForm')">
                        <i class="bi bi-plus"></i> <span class="d-none d-sm-inline">Tambah Warga</span>
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
                                <div class="ktp-upload-box" onclick="window._wargaOpenCam()">
                                    <div class="upload-icon"><i class="bi bi-camera"></i></div>
                                    <h6 class="fw-bold mt-2 mb-1">Buka Kamera Pemindai KTP</h6>
                                    <small class="text-muted">Proses langsung tanpa aplikasi pihak ketiga</small>
                                </div>
                            </div>

                            <div id="ktp-success-area" style="display:none;">
                                <div class="ocr-success-box">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:2rem;"></i>
                                    <h6 class="fw-bold mt-2 mb-1 text-success">KTP Berhasil Difoto!</h6>
                                    <small class="text-muted">Gambar telah di-crop otomatis sesuai kotak panduan</small>
                                    <div class="mt-3">
                                        <img id="ktp-preview-img" src="" class="img-thumbnail rounded mb-2" style="max-height: 120px; display: none;">
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window._wargaResetKtp()">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Foto Ulang
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

    {{-- ============================================================ --}}
    {{-- WEBCAM OVERLAY - DI LUAR CONDITIONAL --}}
    {{-- ============================================================ --}}
    <div id="wargaCamOverlay" class="webcam-overlay">
        <div class="webcam-header">
            <h6 class="mb-0"><i class="bi bi-camera me-2"></i>Scan KTP Warga</h6>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window._wargaCloseCam()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="webcam-body" id="webcamContainer">
            <video id="wargaVideo" autoplay playsinline></video>
            <div id="wargaGuide" class="ktp-guide">
                <div class="guide-text">Posisikan KTP di dalam kotak ini</div>
                <div class="guide-corner top-left"></div>
                <div class="guide-corner top-right"></div>
                <div class="guide-corner bottom-left"></div>
                <div class="guide-corner bottom-right"></div>
            </div>
        </div>
        <div class="webcam-footer">
            <button type="button" class="btn btn-outline-light" onclick="window._wargaCloseCam()">Batal</button>
            <button type="button" class="btn btn-primary" id="btnTakeWargaPic" onclick="window._wargaTakePic()">
                <i class="bi bi-camera-fill me-1"></i> Ambil Foto
            </button>
        </div>
    </div>

    {{-- Hidden Canvas --}}
    <canvas id="wargaCanvas" style="display:none;"></canvas>

    <script>
        (function() {
            var stream = null;

            window._wargaOpenCam = function() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Browser Anda tidak mendukung fitur Web Camera. Gunakan Chrome atau Safari terbaru.');
                    return;
                }

                document.getElementById('wargaCamOverlay').style.display = 'flex';
                
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
                    audio: false
                }).then(function(s) {
                    stream = s;
                    var video = document.getElementById('wargaVideo');
                    video.srcObject = stream;
                    video.play();
                }).catch(function(err) {
                    console.error(err);
                    alert('Gagal mengakses kamera: ' + err.message + '\n\nPastikan Anda memberikan izin akses kamera.');
                    window._wargaCloseCam();
                });
            };

            window._wargaCloseCam = function() {
                document.getElementById('wargaCamOverlay').style.display = 'none';
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
            };

            window._wargaTakePic = function() {
                var btn = document.getElementById('btnTakeWargaPic');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Memproses...';

                var video = document.getElementById('wargaVideo');
                var guide = document.getElementById('wargaGuide');
                var canvas = document.getElementById('wargaCanvas');
                var ctx = canvas.getContext('2d');

                // Kalkulasi letak gambar
                var vw = video.videoWidth;
                var vh = video.videoHeight;
                var cw = video.clientWidth;
                var ch = video.clientHeight;
                var vRatio = vw / vh;
                var cRatio = cw / ch;

                var renderW, renderH;
                if (cRatio > vRatio) {
                    renderW = cw; renderH = cw / vRatio;
                } else {
                    renderH = ch; renderW = ch * vRatio;
                }
                var offsetX = (renderW - cw) / 2;
                var offsetY = (renderH - ch) / 2;
                
                var guideRect = guide.getBoundingClientRect();
                var containerRect = video.parentElement.getBoundingClientRect();
                
                var gX = guideRect.left - containerRect.left;
                var gY = guideRect.top - containerRect.top;
                
                var scale = vw / renderW;
                var cropX = (gX + offsetX) * scale;
                var cropY = (gY + offsetY) * scale;
                var cropW = guideRect.width * scale;
                var cropH = guideRect.height * scale;

                // Set canvas size to crop size
                canvas.width = cropW;
                canvas.height = cropH;

                // Draw video to canvas with offset to act as crop
                ctx.drawImage(video, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);

                // Convert to Blob
                canvas.toBlob(function(blob) {
                    if (!blob) {
                        btn.disabled = false; btn.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Ambil Foto';
                        alert('Gagal mengambil gambar.'); return;
                    }
                    var file = new File([blob], 'scan_ktp.jpg', { type: 'image/jpeg' });
                    
                    var previewUrl = URL.createObjectURL(blob);
                    var imgPreview = document.getElementById('ktp-preview-img');
                    if (imgPreview) { imgPreview.src = previewUrl; imgPreview.style.display = 'block'; }

                    var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                    component.upload('foto_ktp', file, function() {
                        window._wargaCloseCam();
                        btn.disabled = false; btn.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Ambil Foto';

                        var dropzone = document.getElementById('ktp-dropzone-area');
                        var success = document.getElementById('ktp-success-area');
                        if (dropzone) dropzone.style.display = 'none';
                        if (success) success.style.display = 'block';

                        component.call('scanKtp');
                    }, function(error) {
                        btn.disabled = false; btn.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Ambil Foto';
                        alert('Upload gagal. Cek koneksi internet.');
                    }, function() {});
                }, 'image/jpeg', 0.9);
            };

            window._wargaResetKtp = function() {
                var dropzone = document.getElementById('ktp-dropzone-area');
                var success = document.getElementById('ktp-success-area');
                var imgPreview = document.getElementById('ktp-preview-img');
                if (dropzone) dropzone.style.display = 'block';
                if (success) success.style.display = 'none';
                if (imgPreview) { imgPreview.src = ''; imgPreview.style.display = 'none'; }
            };
        })();
    </script>
</div>
