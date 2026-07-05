<div>
    {{-- Shared Assets - loaded on page init --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        /* === Mobile-first crop overlay === */
        .crop-overlay{position:fixed;inset:0;background:rgba(0,0,0,.95);z-index:9999;display:none;flex-direction:column}
        .crop-header{padding:.75rem 1rem;background:#1e1e2e;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .crop-hint{padding:.5rem 1rem;background:#252540;color:#adb5bd;text-align:center;font-size:.8rem;flex-shrink:0}
        .crop-body{flex:1;overflow:hidden;display:flex;align-items:center;justify-content:center;background:#0a0a14;min-height:0}
        .crop-body img{max-width:100%;max-height:100%;display:block}
        .crop-footer{padding:.75rem 1rem;background:#1e1e2e;display:flex;gap:.75rem;justify-content:center;flex-shrink:0}
        .crop-footer .btn{min-width:120px;padding:.6rem 1.25rem;font-weight:600;font-size:.95rem}
        @media(max-width:576px){
            .crop-header{padding:.6rem .75rem}
            .crop-header h6{font-size:.9rem}
            .crop-footer{padding:.6rem .75rem;gap:.5rem}
            .crop-footer .btn{min-width:100px;padding:.55rem 1rem;font-size:.85rem}
        }

        /* === Upload box === */
        .ktp-upload-box{border:2.5px dashed #198754;border-radius:1rem;padding:1.75rem 1rem;text-align:center;cursor:pointer;transition:all .3s;background:linear-gradient(135deg,#f0fdf4,#dcfce7)}
        .ktp-upload-box:hover{border-color:#0f5132;background:linear-gradient(135deg,#d1fae5,#bbf7d0);transform:translateY(-2px);box-shadow:0 8px 20px rgba(25,135,84,.15)}
        .ktp-upload-box .upload-icon{font-size:2.2rem;color:#198754}
        .ocr-success-box{background:linear-gradient(135deg,#e8f5e9,#c8e6c9);border:2px solid #66bb6a;border-radius:1rem;padding:1.25rem;text-align:center}
        .tip-box{background:linear-gradient(135deg,#e8f5e9,#c8e6c9);border-radius:.75rem;padding:.85rem 1rem;border-left:4px solid #198754}

        /* === Shimmer & animations === */
        .scan-shimmer{height:4px;border-radius:2px;overflow:hidden;background:#e0e0e0}
        .scan-shimmer-bar{height:100%;width:100%;background:linear-gradient(90deg,#198754,#20c997,#198754);background-size:200%;animation:shimmer 1.2s infinite linear}
        @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
        .spin{animation:spin-anim 1s linear infinite}
        @keyframes spin-anim{from{transform:rotate(0)}to{transform:rotate(360deg)}}

        /* === Mobile card list (replace table on small screens) === */
        .tamu-card{border:1px solid #e9ecef;border-radius:.75rem;padding:1rem;margin-bottom:.75rem;background:#fff;transition:box-shadow .2s}
        .tamu-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08)}
        .tamu-card .tamu-name{font-weight:600;font-size:1rem}
        .tamu-card .tamu-meta{font-size:.8rem;color:#6c757d}
        @media(min-width:769px){.mobile-cards{display:none}}
        @media(max-width:768px){.desktop-table{display:none!important}}
    </style>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <h3 class="mb-0"><i class="bi bi-people me-2"></i>Buku Tamu</h3>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-success" wire:click="$toggle('showForm')" onclick="getLocation()">
                        <i class="bi bi-person-plus"></i> <span class="d-none d-sm-inline">Input Tamu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            @if($showForm)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0"><i class="bi bi-person-plus me-2"></i>{{ $editMode ? 'Edit Data Tamu' : 'Pendataan Tamu Masuk' }}</h6>
                        <button type="button" class="btn-close btn-close-white" wire:click="resetForm"></button>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form wire:submit.prevent="save">

                        {{-- KTP OCR Section --}}
                        <div class="mb-4" wire:ignore>
                            <div class="tip-box mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-magic text-success me-2" style="font-size:1.2rem;margin-top:2px"></i>
                                    <div>
                                        <strong>Scan KTP Tamu</strong><br>
                                        <small class="text-muted">Foto KTP tamu, crop area-nya, dan data akan terisi otomatis.</small>
                                    </div>
                                </div>
                            </div>
                            <div id="tamu-ktp-dropzone">
                                <div class="ktp-upload-box" onclick="document.getElementById('tamuKtpInput').click()">
                                    <div class="upload-icon"><i class="bi bi-credit-card-2-front"></i></div>
                                    <h6 class="fw-bold mt-2 mb-1">Klik untuk Foto KTP Tamu</h6>
                                    <small class="text-muted">Kamera terbuka otomatis &bull; Maks 8MB</small>
                                </div>
                                <input type="file" id="tamuKtpInput" accept="image/*" capture="environment" class="d-none"
                                    onchange="window._tamuOpenCropper(this)">
                            </div>
                            <div id="tamu-ktp-success" style="display:none;">
                                <div class="ocr-success-box">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:1.8rem"></i>
                                    <h6 class="fw-bold mt-2 mb-1 text-success">KTP Berhasil Di-scan!</h6>
                                    <small class="text-muted">Periksa data yang terisi di bawah</small>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window._tamuResetKtp()">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Scan Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div wire:loading wire:target="scanKtp" class="mb-3">
                            <div class="d-flex align-items-center gap-2 text-success mb-2">
                                <i class="bi bi-cpu spin"></i>
                                <strong class="small">AI membaca data KTP tamu...</strong>
                            </div>
                            <div class="scan-shimmer"><div class="scan-shimmer-bar"></div></div>
                        </div>
                        @error('file_ktp') <div class="text-danger small mb-3"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror

                        <hr>

                        <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Data Tamu</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" required placeholder="Nama lengkap tamu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">NIK</label>
                                <input type="text" class="form-control" wire:model="nik" maxlength="16" placeholder="16 digit (opsional)">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <input type="text" class="form-control" wire:model="alamat" placeholder="Alamat asal tamu (opsional)">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Tujuan Kunjungan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="tujuan" required placeholder="Contoh: Bertamu ke Pak Ahmad">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Lama (Hari)</label>
                                <input type="number" class="form-control" wire:model="lama_kunjungan_hari" min="1" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-camera me-1"></i> Foto Wajah (Opsional)</h6>
                        <div class="mb-3">
                            <input type="file" class="form-control" wire:model="file_wajah" accept="image/*" capture="environment">
                            <div wire:loading wire:target="file_wajah" class="text-info small mt-1"><i class="bi bi-arrow-repeat spin"></i> Mengunggah...</div>
                            @if ($file_wajah)
                                <img src="{{ $file_wajah->temporaryUrl() }}" class="img-thumbnail mt-2 rounded" style="max-height: 80px;">
                            @elseif ($editMode && $foto_wajah)
                                <img src="{{ Storage::url($foto_wajah) }}" class="img-thumbnail mt-2 rounded" style="max-height: 80px;">
                            @endif
                        </div>

                        <!-- Geolocation -->
                        <div class="alert alert-info py-2 small mb-3" wire:ignore>
                            <i class="bi bi-geo-alt me-1"></i> <span id="geo-status">Mendapatkan lokasi...</span>
                        </div>
                        <input type="hidden" wire:model="lat" id="lat">
                        <input type="hidden" wire:model="lng" id="lng">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="row g-2">
                        <div class="col-8 col-md-4">
                            <input type="text" class="form-control" placeholder="Cari nama / tujuan..." wire:model.live.debounce.300ms="search">
                        </div>
                        <div class="col-4 col-md-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Desktop Table --}}
                <div class="card-body p-0 desktop-table table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Tamu</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tamus as $tamu)
                            <tr>
                                <td><small>{{ $tamu->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($tamu->foto_wajah)
                                            <img src="{{ Storage::url($tamu->foto_wajah) }}" class="rounded me-2" style="width:36px;height:36px;object-fit:cover">
                                        @else
                                            <div class="bg-secondary rounded d-flex justify-content-center align-items-center text-white me-2" style="width:36px;height:36px"><i class="bi bi-person"></i></div>
                                        @endif
                                        <div>
                                            <strong>{{ $tamu->name }}</strong><br>
                                            <small class="text-muted">{{ $tamu->alamat ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $tamu->tujuan }}<br><small class="text-muted">{{ $tamu->lama_kunjungan_hari }} Hari</small></td>
                                <td>
                                    @if($tamu->waktu_keluar)
                                        <span class="badge bg-secondary"><i class="bi bi-door-closed"></i> Selesai</span>
                                    @elseif($tamu->waktu_masuk)
                                        <span class="badge bg-success"><i class="bi bi-person-bounding-box"></i> Di Dalam</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$tamu->waktu_masuk)
                                        <button wire:click="checkIn({{ $tamu->id }})" class="btn btn-sm btn-success mb-1"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
                                    @elseif(!$tamu->waktu_keluar)
                                        <button wire:click="checkOut({{ $tamu->id }})" class="btn btn-sm btn-danger mb-1"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                                    @endif
                                    <button wire:click="edit({{ $tamu->id }})" class="btn btn-sm btn-warning mb-1"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="delete({{ $tamu->id }})" class="btn btn-sm btn-outline-danger mb-1" onclick="confirm('Hapus data tamu?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada tamu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="card-body mobile-cards">
                    @forelse($tamus as $tamu)
                    <div class="tamu-card">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                @if($tamu->foto_wajah)
                                    <img src="{{ Storage::url($tamu->foto_wajah) }}" class="rounded me-2" style="width:42px;height:42px;object-fit:cover">
                                @else
                                    <div class="bg-secondary rounded d-flex justify-content-center align-items-center text-white me-2" style="width:42px;height:42px"><i class="bi bi-person"></i></div>
                                @endif
                                <div>
                                    <div class="tamu-name">{{ $tamu->name }}</div>
                                    <div class="tamu-meta">{{ $tamu->alamat ?? 'Tanpa Alamat' }}</div>
                                </div>
                            </div>
                            @if($tamu->waktu_keluar)
                                <span class="badge bg-secondary"><i class="bi bi-door-closed"></i> Selesai</span>
                            @elseif($tamu->waktu_masuk)
                                <span class="badge bg-success"><i class="bi bi-person-bounding-box"></i> Di Dalam</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                            @endif
                        </div>
                        <div class="tamu-meta mb-2">
                            <i class="bi bi-bullseye me-1"></i>{{ $tamu->tujuan }} &bull; {{ $tamu->lama_kunjungan_hari }} Hari<br>
                            <i class="bi bi-clock me-1"></i>{{ $tamu->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="d-flex gap-1 flex-wrap">
                            @if(!$tamu->waktu_masuk)
                                <button wire:click="checkIn({{ $tamu->id }})" class="btn btn-sm btn-success"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</button>
                            @elseif(!$tamu->waktu_keluar)
                                <button wire:click="checkOut({{ $tamu->id }})" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-left me-1"></i>Keluar</button>
                            @endif
                            <button wire:click="edit({{ $tamu->id }})" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
                            <button wire:click="delete({{ $tamu->id }})" class="btn btn-sm btn-outline-danger" onclick="confirm('Hapus?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">Belum ada tamu.</div>
                    @endforelse
                </div>

                <div class="card-footer">{{ $tamus->links() }}</div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- Crop overlay & scripts - OUTSIDE conditional --}}
    {{-- ============================================ --}}
    <div id="tamuCropOverlay" class="crop-overlay">
        <div class="crop-header">
            <h6 class="mb-0"><i class="bi bi-crop me-2"></i>Potong Area KTP</h6>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window._tamuCloseCropper()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="crop-hint">
            <i class="bi bi-info-circle me-1"></i>Geser & perbesar kotak biru agar pas membingkai KTP fisik saja
        </div>
        <div class="crop-body">
            <img id="tamuCropImage" src="" alt="KTP Tamu">
        </div>
        <div class="crop-footer">
            <button type="button" class="btn btn-outline-light" onclick="window._tamuCloseCropper()">Batal</button>
            <button type="button" class="btn btn-success" id="btnTamuCropScan" onclick="window._tamuDoCrop()">
                <i class="bi bi-upc-scan me-1"></i> Crop & Scan
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // === Tamu KTP Cropper ===
        (function() {
            var cropper = null;

            window._tamuOpenCropper = function(input) {
                var file = input.files[0];
                if (!file) return;
                if (file.size > 8 * 1024 * 1024) { alert('File terlalu besar. Maks 8MB.'); input.value = ''; return; }
                if (!file.type.startsWith('image/')) { alert('Hanya file gambar.'); input.value = ''; return; }

                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('tamuCropImage');
                    img.src = e.target.result;
                    document.getElementById('tamuCropOverlay').style.display = 'flex';
                    setTimeout(function() {
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(img, { viewMode: 1, autoCropArea: 0.85, responsive: true, background: true });
                    }, 300);
                };
                reader.readAsDataURL(file);
            };

            window._tamuCloseCropper = function() {
                document.getElementById('tamuCropOverlay').style.display = 'none';
                if (cropper) { cropper.destroy(); cropper = null; }
            };

            window._tamuDoCrop = function() {
                if (!cropper) return;
                var btn = document.getElementById('btnTamuCropScan');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Memproses...';

                cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob(function(blob) {
                    if (!blob) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan'; alert('Gagal crop.'); return; }
                    var croppedFile = new File([blob], 'ktp_tamu.jpg', { type: 'image/jpeg' });
                    var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));

                    component.upload('file_ktp', croppedFile, function() {
                        window._tamuCloseCropper();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan';
                        var dz = document.getElementById('tamu-ktp-dropzone');
                        var sc = document.getElementById('tamu-ktp-success');
                        if (dz) dz.style.display = 'none';
                        if (sc) sc.style.display = 'block';
                        component.call('scanKtp');
                    }, function(err) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan';
                        console.error(err);
                        alert('Upload gagal. Cek koneksi.');
                    }, function() {});
                }, 'image/jpeg', 0.85);
            };

            window._tamuResetKtp = function() {
                var dz = document.getElementById('tamu-ktp-dropzone');
                var sc = document.getElementById('tamu-ktp-success');
                if (dz) dz.style.display = 'block';
                if (sc) sc.style.display = 'none';
                var input = document.getElementById('tamuKtpInput');
                if (input) input.value = '';
            };
        })();

        // === Geolocation ===
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        var el = document.getElementById('geo-status');
                        if (el) el.innerText = 'Terkunci: ' + pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6);
                        var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                        component.set('lat', pos.coords.latitude);
                        component.set('lng', pos.coords.longitude);
                    },
                    function() {
                        var el = document.getElementById('geo-status');
                        if (el) el.innerText = 'Gagal (Izin Ditolak)';
                    }
                );
            }
        }
    </script>
</div>
