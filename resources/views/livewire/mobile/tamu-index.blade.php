<div>
    <style>
        .ocr-scanner-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.95);
            z-index: 9999;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        }
        .scan-box {
            width: 280px; height: 180px;
            border: 2px solid rgba(16, 185, 129, 0.6);
            border-radius: 16px;
            position: relative; overflow: hidden;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);
        }
        .scan-line {
            width: 100%; height: 4px;
            background: #10b981;
            box-shadow: 0 0 15px #10b981, 0 0 30px #10b981;
            position: absolute; top: 0;
            animation: scanAnim 2s infinite ease-in-out;
        }
        @keyframes scanAnim {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        
        .upload-area-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px dashed #34d399;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .search-box {
            background: white;
            border-radius: 16px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-bottom: 20px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .search-box input {
            border: none;
            box-shadow: none;
            background: transparent;
        }
        .search-box input:focus { outline: none; box-shadow: none; }
    </style>

    @if(!$showForm)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 text-success">Buku Tamu</h5>
            <span class="badge bg-success rounded-pill px-3 py-2">{{ $tamus->total() }} Data</span>
        </div>

        <div class="search-box">
            <i class="bi bi-search text-muted ms-2"></i>
            <input type="text" class="form-control" placeholder="Cari nama tamu / tujuan..." wire:model.live.debounce.300ms="search">
        </div>

        <div>
            @forelse($tamus as $tamu)
                <div class="soft-card position-relative border-start border-4 {{ $tamu->waktu_keluar ? 'border-secondary' : ($tamu->waktu_masuk ? 'border-success' : 'border-warning') }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            @if($tamu->foto_wajah)
                                <img src="{{ Storage::url($tamu->foto_wajah) }}" class="rounded-circle shadow-sm me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <div class="bg-secondary bg-opacity-25 rounded-circle d-flex justify-content-center align-items-center text-dark me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $tamu->name }}</h6>
                                <span class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $tamu->alamat ?? 'Tanpa Alamat' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            @if($tamu->waktu_keluar)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill"><i class="bi bi-door-closed me-1"></i>Selesai</span>
                            @elseif($tamu->waktu_masuk)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-person-bounding-box me-1"></i>Di Dalam</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-light rounded p-2 mb-3 mt-2">
                        <div class="small fw-semibold text-dark"><i class="bi bi-bullseye text-primary me-1"></i> {{ $tamu->tujuan }}</div>
                        <div class="small text-muted mt-1"><i class="bi bi-clock me-1"></i> {{ $tamu->created_at->format('d M Y, H:i') }} &bull; {{ $tamu->lama_kunjungan_hari }} Hari</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        @if(!$tamu->waktu_masuk)
                            <button wire:click="checkIn({{ $tamu->id }})" class="btn btn-success flex-fill fw-medium shadow-sm" style="border-radius: 12px;"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>
                        @elseif(!$tamu->waktu_keluar)
                            <button wire:click="checkOut({{ $tamu->id }})" class="btn btn-danger flex-fill fw-medium shadow-sm" style="border-radius: 12px;"><i class="bi bi-box-arrow-left me-1"></i> Keluar</button>
                        @endif
                        <button wire:click="edit({{ $tamu->id }})" class="btn btn-light text-primary fw-medium" style="border-radius: 12px;"><i class="bi bi-pencil"></i></button>
                        <button wire:click="delete({{ $tamu->id }})" class="btn btn-light text-danger fw-medium" style="border-radius: 12px;" onclick="confirm('Hapus tamu ini?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-journal-x text-muted fs-1"></i>
                    </div>
                    <h6 class="fw-bold">Belum Ada Tamu</h6>
                    <p class="text-muted small">Catat tamu yang berkunjung dengan menekan tombol plus.</p>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $tamus->links() }}
            </div>
        </div>

        <button class="fab" style="background: linear-gradient(135deg, #10b981, #047857); box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);" wire:click="$toggle('showForm')" onclick="window._mobileGetLocation()">
            <i class="bi bi-person-plus"></i>
        </button>

    @else
        <!-- Form Section -->
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px;" wire:click="resetForm">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h5 class="fw-bold m-0 text-success">{{ $editMode ? 'Edit Tamu' : 'Input Tamu' }}</h5>
        </div>

        <div class="soft-card mb-4 border-top border-4 border-success">
            <h6 class="fw-bold mb-3"><i class="bi bi-magic text-success me-2"></i>Scan KTP Cepat</h6>
            <div class="upload-area-success" onclick="document.getElementById('mobileTamuKtpInput').click()">
                <div class="text-success mb-2" style="font-size: 2.5rem;"><i class="bi bi-camera"></i></div>
                <h6 class="fw-bold text-success mb-1">Ambil Foto KTP</h6>
                <p class="text-success opacity-75 small m-0">Ekstrak NIK dan Nama dengan AI</p>
            </div>
            <input type="file" id="mobileTamuKtpInput" accept="image/*" capture="environment" class="d-none" onchange="window._mobileTamuProcessKtp(this)">
        </div>

        <div class="soft-card">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" wire:model="name" required placeholder="Nama Lengkap">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">NIK (Opsional)</label>
                    <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="nik" placeholder="16 Digit NIK">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Alamat Asal (Opsional)</label>
                    <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="alamat" placeholder="Alamat asal tamu">
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Tujuan Kunjungan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="tujuan" required placeholder="Cth: Bertemu Pak RT">
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Lama (Hari)</label>
                    <input type="number" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="lama_kunjungan_hari" required min="1">
                </div>

                <div class="alert alert-success bg-success bg-opacity-10 border-0 py-2 px-3 small mb-4 d-flex align-items-center rounded-3" wire:ignore>
                    <i class="bi bi-geo-alt-fill text-success fs-5 me-2"></i> 
                    <span id="geo-status-mobile" class="text-success fw-medium">Menyesuaikan Lokasi...</span>
                </div>
                <input type="hidden" wire:model="lat" id="lat-mobile">
                <input type="hidden" wire:model="lng" id="lng-mobile">

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm" style="border-radius: 16px;">
                    <i class="bi bi-check-circle me-2"></i> Simpan Data Tamu
                </button>
            </form>
        </div>
    @endif

    <!-- OCR Loading Overlay -->
    <div wire:loading wire:target="scanKtp">
        <div class="ocr-scanner-overlay">
            <div class="scan-box mb-4">
                <div class="scan-line"></div>
            </div>
            <h4 class="text-white fw-bold mb-1">Menganalisis KTP</h4>
            <p class="text-light opacity-75">AI sedang menarik nama & NIK...</p>
        </div>
    </div>

    <!-- Script for mobile processing -->
    <script>
        window._mobileGetLocation = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        var el = document.getElementById('geo-status-mobile');
                        if (el) el.innerHTML = 'Lokasi Terkunci <i class="bi bi-check2"></i>';
                        var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                        component.set('lat', pos.coords.latitude);
                        component.set('lng', pos.coords.longitude);
                    },
                    function() {
                        var el = document.getElementById('geo-status-mobile');
                        if (el) el.innerHTML = 'Akses Lokasi Ditolak <i class="bi bi-x-circle text-danger"></i>';
                    },
                    { enableHighAccuracy: true, timeout: 5000 }
                );
            }
        };

        window._mobileTamuProcessKtp = function(input) {
            var file = input.files[0];
            if (!file) return;
            
            var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
            
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');
                    var maxDim = 1200;
                    var w = img.width, h = img.height;
                    
                    if (w > maxDim || h > maxDim) {
                        if (w > h) { h = Math.round((h * maxDim) / w); w = maxDim; }
                        else { w = Math.round((w * maxDim) / h); h = maxDim; }
                    }
                    
                    canvas.width = w; canvas.height = h;
                    ctx.drawImage(img, 0, 0, w, h);
                    
                    canvas.toBlob(function(blob) {
                        if (!blob) { alert('Gagal memproses gambar'); return; }
                        var compressedFile = new File([blob], 'ktp_tamu.jpg', { type: 'image/jpeg' });
                        component.upload('file_ktp', compressedFile, function() {
                            component.call('scanKtp');
                        }, function(error) {
                            alert('Gagal mengupload. Periksa koneksi internet.');
                        }, function() {});
                    }, 'image/jpeg', 0.80);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };
    </script>
</div>
