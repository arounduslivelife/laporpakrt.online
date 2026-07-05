<div>
    <style>
        .ktp-upload-box{border:2.5px dashed #198754;border-radius:1rem;padding:1.75rem 1rem;text-align:center;cursor:pointer;transition:all .3s;background:linear-gradient(135deg,#f0fdf4,#dcfce7)}
        .ktp-upload-box:hover{border-color:#0f5132;background:linear-gradient(135deg,#d1fae5,#bbf7d0);transform:translateY(-2px);box-shadow:0 8px 20px rgba(25,135,84,.15)}
        .ktp-upload-box .upload-icon{font-size:2.2rem;color:#198754}
        .ocr-success-box{background:linear-gradient(135deg,#e8f5e9,#c8e6c9);border:2px solid #66bb6a;border-radius:1rem;padding:1.25rem;text-align:center}
        .tip-box{background:linear-gradient(135deg,#e8f5e9,#c8e6c9);border-radius:.75rem;padding:.85rem 1rem;border-left:4px solid #198754}
        .scan-shimmer{height:4px;border-radius:2px;overflow:hidden;background:#e0e0e0}
        .scan-shimmer-bar{height:100%;width:100%;background:linear-gradient(90deg,#198754,#20c997,#198754);background-size:200%;animation:shimmer 1.2s infinite linear}
        @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
        .spin{animation:spin-anim 1s linear infinite}
        @keyframes spin-anim{from{transform:rotate(0)}to{transform:rotate(360deg)}}
        .tamu-card{border:1px solid #e9ecef;border-radius:.75rem;padding:1rem;margin-bottom:.75rem;background:#fff;transition:box-shadow .2s}
        .tamu-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08)}
        .tamu-card .tamu-name{font-weight:600;font-size:1rem}
        .tamu-card .tamu-meta{font-size:.8rem;color:#6c757d}
        @media(min-width:769px){.mobile-cards{display:none}}
        @media(max-width:768px){.desktop-table{display:none!important}}
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
                    <button class="btn btn-success d-none d-md-inline-block" wire:click="$toggle('showForm')" onclick="getLocation()">
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

                        <div class="mb-4" wire:ignore>
                            <div class="tip-box mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-magic text-success me-2" style="font-size:1.2rem;margin-top:2px"></i>
                                    <div>
                                        <strong>Scan KTP Tamu Otomatis</strong><br>
                                        <small class="text-muted">Arahkan kamera agar KTP pas di kotak, dan AI akan membacanya.</small>
                                    </div>
                                </div>
                            </div>
                            <div id="tamu-ktp-dropzone">
                                <div class="ktp-upload-box" onclick="document.getElementById('tamuFileInput').click()">
                                    <div class="upload-icon"><i class="bi bi-camera"></i></div>
                                    <h6 class="fw-bold mt-2 mb-1">Klik untuk Ambil Foto KTP Tamu</h6>
                                    <small class="text-muted">Kamera bawaan HP akan terbuka untuk hasil lebih tajam</small>
                                </div>
                                <input type="file" id="tamuFileInput" accept="image/*" capture="environment" class="d-none"
                                    onchange="window._tamuProcessKtp(this)">
                            </div>
                            <div id="tamu-ktp-success" style="display:none;">
                                <div class="ocr-success-box">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size:1.8rem"></i>
                                    <h6 class="fw-bold mt-2 mb-1 text-success">KTP Berhasil Difoto!</h6>
                                    <div class="mt-3">
                                        <img id="ktp-preview-img" src="" class="img-thumbnail rounded mb-2" style="max-height: 120px; display: none; margin: 0 auto;">
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window._tamuResetKtp()">
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
                                <p class="text-light opacity-75">AI membaca data KTP tamu</p>
                            </div>
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

                <div class="card-footer">{{ $tamus->links() }}</div>
            </div>
        </div>
    </div>

    <script>
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

        // === KTP Upload Logic ===
        (function() {
            window._tamuProcessKtp = function(input) {
                var file = input.files[0];
                if (!file) return;

                var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                var dz = document.getElementById('tamu-ktp-dropzone');
                var sc = document.getElementById('tamu-ktp-success');
                var imgPreview = document.getElementById('ktp-preview-img');
                
                if (dz) dz.style.display = 'none';
                if (sc) sc.style.display = 'block';

                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        var maxDim = 1600;
                        var w = img.width, h = img.height;

                        if (w > maxDim || h > maxDim) {
                            if (w > h) { h = Math.round((h * maxDim) / w); w = maxDim; }
                            else { w = Math.round((w * maxDim) / h); h = maxDim; }
                        }
                        canvas.width = w; canvas.height = h;
                        ctx.drawImage(img, 0, 0, w, h);

                        canvas.toBlob(function(blob) {
                            if (!blob) { alert('Gagal memproses gambar'); window._tamuResetKtp(); return; }
                            var previewUrl = URL.createObjectURL(blob);
                            if (imgPreview) { imgPreview.src = previewUrl; imgPreview.style.display = 'block'; }
                            
                            var compressedFile = new File([blob], 'ktp_tamu.jpg', { type: 'image/jpeg' });
                            
                            component.upload('file_ktp', compressedFile, function() {
                                component.call('scanKtp');
                            }, function(error) {
                                alert('Upload gagal. Cek koneksi.');
                                window._tamuResetKtp();
                            }, function() {});
                        }, 'image/jpeg', 0.85);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            };

            window._tamuResetKtp = function() {
                var dz = document.getElementById('tamu-ktp-dropzone');
                var sc = document.getElementById('tamu-ktp-success');
                var imgPreview = document.getElementById('ktp-preview-img');
                var input = document.getElementById('tamuFileInput');
                
                if (dz) dz.style.display = 'block';
                if (sc) sc.style.display = 'none';
                if (imgPreview) { imgPreview.src = ''; imgPreview.style.display = 'none'; }
                if (input) input.value = '';
            };
        })();
    </script>
</div>
