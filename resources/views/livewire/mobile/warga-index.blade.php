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
            border: 2px solid rgba(14, 165, 233, 0.6);
            border-radius: 16px;
            position: relative; overflow: hidden;
            box-shadow: 0 0 30px rgba(14, 165, 233, 0.4);
        }
        .scan-line {
            width: 100%; height: 4px;
            background: #0ea5e9;
            box-shadow: 0 0 15px #0ea5e9, 0 0 30px #0ea5e9;
            position: absolute; top: 0;
            animation: scanAnim 2s infinite ease-in-out;
        }
        @keyframes scanAnim {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        
        .upload-area {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px dashed #38bdf8;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .upload-icon { font-size: 3rem; color: #0ea5e9; margin-bottom: 10px; }
        
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
        .search-box input:focus {
            outline: none;
            box-shadow: none;
        }
    </style>

    @if(!$showForm)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0">Data Warga</h5>
            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $wargas->total() }} Total</span>
        </div>

        <div class="search-box">
            <i class="bi bi-search text-muted ms-2"></i>
            <input type="text" class="form-control" placeholder="Cari nama atau NIK..." wire:model.live.debounce.300ms="search">
        </div>

        <div>
            @forelse($wargas as $warga)
                <div class="soft-card">
                    <div class="d-flex align-items-center mb-3">
                        @if($warga->foto_path)
                            <img src="{{ Storage::url($warga->foto_path) }}" class="rounded-circle shadow-sm me-3" style="width: 54px; height: 54px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle shadow-sm d-flex justify-content-center align-items-center text-white me-3" style="width: 54px; height: 54px; font-size: 1.5rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;">
                                {{ substr($warga->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $warga->name }}</h6>
                            <div class="text-muted small mb-1"><i class="bi bi-credit-card me-1"></i> {{ $warga->nik ? substr($warga->nik, 0, 4) . '****' . substr($warga->nik, -4) : '-' }}</div>
                            @if($warga->status_domisili == 'Tetap')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-25 px-2"><i class="bi bi-house-check me-1"></i>Tetap</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill border border-warning border-opacity-25 px-2"><i class="bi bi-house-dash me-1"></i>Kontrak</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 border-top pt-3 mt-1">
                        <button wire:click="edit({{ $warga->id }})" class="btn btn-light text-primary flex-fill fw-medium" style="border-radius: 12px;"><i class="bi bi-pencil me-1"></i> Edit</button>
                        <button wire:click="delete({{ $warga->id }})" class="btn btn-light text-danger flex-fill fw-medium" style="border-radius: 12px;" onclick="confirm('Hapus warga ini?') || event.stopImmediatePropagation()"><i class="bi bi-trash me-1"></i> Hapus</button>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-inbox text-muted fs-1"></i>
                    </div>
                    <h6 class="fw-bold">Belum Ada Data Warga</h6>
                    <p class="text-muted small">Tambahkan warga pertama Anda dengan menekan tombol plus di bawah.</p>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $wargas->links() }}
            </div>
        </div>

        <button class="fab" wire:click="$toggle('showForm')">
            <i class="bi bi-plus"></i>
        </button>

    @else
        <!-- Form Section -->
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px;" wire:click="resetForm">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h5 class="fw-bold m-0">{{ $editMode ? 'Edit Warga' : 'Warga Baru' }}</h5>
        </div>

        <div class="soft-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-magic text-primary me-2"></i>Scan KTP Otomatis</h6>
            <div class="upload-area" onclick="document.getElementById('mobileKtpInput').click()">
                <div class="upload-icon"><i class="bi bi-camera"></i></div>
                <h6 class="fw-bold text-primary mb-1">Ambil Foto KTP</h6>
                <p class="text-muted small m-0">Data akan terisi otomatis oleh AI</p>
            </div>
            <input type="file" id="mobileKtpInput" accept="image/*" capture="environment" class="d-none" onchange="window._mobileWargaProcessKtp(this)">
            
            @error('foto_ktp') <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
        </div>

        <div class="soft-card">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" wire:model="name" required placeholder="Sesuai KTP">
                </div>
                
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">NIK</label>
                        <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="nik" placeholder="16 Digit">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">No KK</label>
                        <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="no_kk" placeholder="16 Digit">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">Jenis Kelamin</label>
                        <select class="form-select bg-light border-0" style="border-radius: 12px;" wire:model="jenis_kelamin">
                            <option value="">Pilih</option>
                            <option value="LAKI-LAKI">Laki-laki</option>
                            <option value="PEREMPUAN">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">Domisili</label>
                        <select class="form-select bg-light border-0" style="border-radius: 12px;" wire:model="status_domisili">
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak/Kos</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">Tempat Lahir</label>
                        <input type="text" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="tempat_lahir">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small fw-semibold">Tgl Lahir</label>
                        <input type="date" class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="tanggal_lahir">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Alamat Lengkap</label>
                    <textarea class="form-control bg-light border-0" style="border-radius: 12px;" wire:model="alamat" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
                    <i class="bi bi-check-circle me-2"></i> Simpan Data Warga
                </button>
            </form>
        </div>

        <!-- Duplicate NIK Warning Bottom Sheet -->
        @if($showDuplicateWarning)
        <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:click="$set('showDuplicateWarning', false)"></div>
        <div class="position-fixed bottom-0 start-0 w-100 bg-white" style="z-index: 1060; border-radius: 24px 24px 0 0; max-height: 85vh; overflow-y: auto; padding: 24px; transition: transform 0.3s ease-out;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-warning-emphasis"><i class="bi bi-exclamation-triangle me-2"></i> Peringatan Duplikasi</h6>
                <button class="btn-close" wire:click="$set('showDuplicateWarning', false)"></button>
            </div>
            
            <div class="text-center mb-4 mt-3">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-exclamation text-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="fw-bold text-dark">NIK Sudah Terdaftar</h6>
                <p class="text-muted small mb-0">Sistem mendeteksi NIK <strong>{{ $nik }}</strong> terdaftar atas nama:</p>
                <h6 class="fw-bold text-primary mt-2">{{ $duplicateInfo['name'] ?? '-' }}</h6>
                <p class="text-muted small">Di wilayah: <strong>{{ $duplicateInfo['rt'] ?? '-' }}</strong></p>
                
                <div class="alert alert-light border small text-start mt-3" style="border-radius: 12px;">
                    Data mungkin belum dihapus oleh RT asal. Jika Anda yakin ini warga Anda (misal baru pindah), Anda dapat melanjutkan.
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light flex-fill py-3 fw-bold" style="border-radius: 16px;" wire:click="$set('showDuplicateWarning', false)">Batal</button>
                <button type="button" class="btn btn-warning flex-fill py-3 fw-bold text-dark" style="border-radius: 16px;" wire:click="confirmSave">
                    Tetap Tambahkan
                </button>
            </div>
        </div>
        @endif
    @endif

    <!-- OCR Loading Overlay -->
    <div wire:loading wire:target="scanKtp">
        <div class="ocr-scanner-overlay">
            <div class="scan-box mb-4">
                <div class="scan-line"></div>
            </div>
            <h4 class="text-white fw-bold mb-1">Menganalisis KTP</h4>
            <p class="text-light opacity-75">AI sedang mengekstrak identitas...</p>
        </div>
    </div>

    <!-- Script for mobile KTP processing -->
    <script>
        window._mobileWargaProcessKtp = function(input) {
            var file = input.files[0];
            if (!file) return;
            
            var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
            
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');
                    var maxDim = 1200; // Smaller for mobile upload speed
                    var w = img.width;
                    var h = img.height;
                    
                    if (w > maxDim || h > maxDim) {
                        if (w > h) { h = Math.round((h * maxDim) / w); w = maxDim; }
                        else { w = Math.round((w * maxDim) / h); h = maxDim; }
                    }
                    
                    canvas.width = w; canvas.height = h;
                    ctx.drawImage(img, 0, 0, w, h);
                    
                    canvas.toBlob(function(blob) {
                        if (!blob) { alert('Gagal memproses gambar'); return; }
                        
                        var compressedFile = new File([blob], 'ktp.jpg', { type: 'image/jpeg' });
                        
                        // Fake a scan loading start
                        component.upload('foto_ktp', compressedFile, function() {
                            component.call('scanKtp');
                        }, function(error) {
                            alert('Gagal mengupload. Periksa sinyal internet.');
                        }, function() {});
                        
                    }, 'image/jpeg', 0.80);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };
    </script>
</div>
