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
            {{-- Cropper.js Assets --}}
            <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
            <style>
                .ocr-dropzone-sm {
                    border: 2px dashed #ced4da; border-radius: .75rem; padding: 1.5rem 1rem;
                    text-align: center; cursor: pointer; transition: all .3s ease;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                }
                .ocr-dropzone-sm:hover { border-color: #0d6efd; background: #e7f1ff; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(13,110,253,.1); }
                .cropper-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.85); z-index: 1060; display: flex; align-items: center; justify-content: center; }
                .cropper-modal-content { background: #fff; border-radius: 1rem; width: 95%; max-width: 700px; max-height: 90vh; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.4); }
                .cropper-modal-body { padding: 1rem; background: #1a1a2e; }
                .cropper-modal-body img { max-width: 100%; display: block; }
                .cropper-modal-footer { padding: 1rem 1.5rem; background: #fff; display: flex; gap: .75rem; justify-content: flex-end; }
                .scan-indicator { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1rem; border-radius: .5rem; font-size: .85rem; }
                .spin { animation: spin 1s linear infinite; }
                @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
                .scan-progress-sm { height: 3px; border-radius: 2px; overflow: hidden; background: #e9ecef; }
                .scan-progress-bar-sm { height: 100%; background: linear-gradient(90deg, #0d6efd, #0dcaf0, #0d6efd); background-size: 200%; animation: shimmer 1.5s infinite; }
                @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
            </style>

            <div class="card mb-4 border-0 shadow-sm" x-data="wargaKtpScanner()">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-person-plus me-2 text-primary"></i>
                            {{ $editMode ? 'Edit Data Warga' : 'Input Data Warga Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="resetForm"></button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        {{-- OCR Quick Scan Section --}}
                        <div class="alert alert-info border-0 d-flex align-items-start mb-4" style="background: linear-gradient(135deg, #e7f1ff, #d0e2ff); border-radius: .75rem;">
                            <i class="bi bi-lightbulb text-primary me-3" style="font-size: 1.5rem; margin-top: 2px;"></i>
                            <div>
                                <strong>Tip Cepat:</strong> Upload foto KTP di bawah untuk mengisi form secara otomatis menggunakan teknologi OCR. Anda tetap bisa mengedit hasilnya secara manual.
                            </div>
                        </div>

                        <div class="row">
                            {{-- Left: Form Fields --}}
                            <div class="col-lg-7">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Data Identitas</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
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
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tempat Lahir</label>
                                        <input type="text" class="form-control" wire:model="tempat_lahir" placeholder="Kota kelahiran">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" class="form-control" wire:model="tanggal_lahir">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <textarea class="form-control" wire:model="alamat" rows="2" placeholder="Alamat lengkap sesuai KTP"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Photo & KTP Upload --}}
                            <div class="col-lg-5">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3"><i class="bi bi-camera me-1"></i> Foto & Dokumen</h6>

                                {{-- Foto Wajah --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Foto Wajah</label>
                                    <input type="file" class="form-control" wire:model="foto_wajah" accept="image/*" capture="environment">
                                    <div wire:loading wire:target="foto_wajah" class="text-info small mt-1"><i class="bi bi-arrow-repeat spin"></i> Mengunggah...</div>
                                    @if ($foto_wajah)
                                        <img src="{{ $foto_wajah->temporaryUrl() }}" class="img-thumbnail mt-2 rounded" style="max-height: 100px;">
                                    @elseif ($editMode && $foto_path)
                                        <img src="{{ Storage::url($foto_path) }}" class="img-thumbnail mt-2 rounded" style="max-height: 100px;">
                                    @endif
                                </div>

                                {{-- KTP with OCR --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Foto KTP <span class="badge bg-primary-subtle text-primary ms-1">OCR</span></label>

                                    {{-- State: Ready to upload --}}
                                    <div x-show="!ktpReady">
                                        <div class="ocr-dropzone-sm" x-on:click="$refs.ktpInput.click()">
                                            <i class="bi bi-credit-card-2-front text-primary" style="font-size: 2rem;"></i>
                                            <p class="mb-1 mt-2 fw-semibold small">Klik untuk foto KTP</p>
                                            <span class="text-muted" style="font-size: .75rem;">Akan otomatis mengisi form di samping</span>
                                        </div>
                                        <input type="file" x-ref="ktpInput" class="d-none" accept="image/*" capture="environment"
                                            x-on:change="handleKtpFile($event.target.files[0])">
                                    </div>

                                    {{-- State: KTP uploaded & scanned --}}
                                    <div x-show="ktpReady" x-cloak>
                                        <div class="border rounded p-3 text-center" style="background: #f0fdf4;">
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                            <p class="mb-1 mt-1 fw-semibold small text-success">KTP berhasil di-scan!</p>
                                            <button type="button" class="btn btn-outline-secondary btn-sm mt-1" x-on:click="resetKtp()">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Ganti KTP
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Scanning indicator --}}
                                    <div wire:loading wire:target="scanKtp" class="mt-2">
                                        <div class="scan-indicator bg-info-subtle text-info">
                                            <i class="bi bi-cpu"></i> <span>AI membaca KTP...</span>
                                        </div>
                                        <div class="scan-progress-sm mt-1">
                                            <div class="scan-progress-bar-sm" style="width:100%"></div>
                                        </div>
                                    </div>

                                    @if ($editMode && $ktp_path && !$foto_ktp)
                                        <img src="{{ Storage::url($ktp_path) }}" class="img-thumbnail mt-2 rounded" style="max-height: 100px;">
                                    @endif
                                    @error('foto_ktp') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
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

                {{-- Cropper Modal (Full-screen overlay) --}}
                <template x-if="showCropperModal">
                    <div class="cropper-modal-overlay" x-transition>
                        <div class="cropper-modal-content">
                            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="mb-0"><i class="bi bi-crop me-2 text-primary"></i>Potong Area KTP</h6>
                                <button type="button" class="btn-close" x-on:click="closeCropperModal()"></button>
                            </div>
                            <p class="px-3 pt-2 mb-0 text-muted small">Seret kotak biru untuk membingkai area fisik KTP saja. Buang latar belakang untuk akurasi lebih tinggi.</p>
                            <div class="cropper-modal-body">
                                <img id="cropperImageWarga" :src="cropImageUrl" style="max-width: 100%; display: block;">
                            </div>
                            <div class="cropper-modal-footer">
                                <button type="button" class="btn btn-outline-secondary" x-on:click="closeCropperModal()">Batal</button>
                                <button type="button" class="btn btn-primary" x-bind:disabled="cropUploading" x-on:click="cropAndUpload()">
                                    <span x-show="!cropUploading"><i class="bi bi-upc-scan me-1"></i> Crop & Scan OCR</span>
                                    <span x-show="cropUploading"><i class="bi bi-arrow-repeat spin me-1"></i> Memproses...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <script>
                function wargaKtpScanner() {
                    return {
                        showCropperModal: false,
                        cropImageUrl: null,
                        cropper: null,
                        cropUploading: false,
                        ktpReady: false,

                        init() {
                            this.ktpReady = !!@this.get('foto_ktp');
                        },

                        handleKtpFile(file) {
                            if (!file) return;
                            if (file.size > 8 * 1024 * 1024) { alert('File terlalu besar. Maksimal 8MB.'); return; }
                            if (!file.type.startsWith('image/')) { alert('Hanya file gambar yang diperbolehkan.'); return; }

                            this.cropImageUrl = URL.createObjectURL(file);
                            this.showCropperModal = true;

                            this.$nextTick(() => {
                                setTimeout(() => {
                                    const img = document.getElementById('cropperImageWarga');
                                    if (this.cropper) this.cropper.destroy();
                                    this.cropper = new Cropper(img, {
                                        viewMode: 1,
                                        autoCropArea: 0.85,
                                        responsive: true,
                                        background: true,
                                    });
                                }, 300);
                            });
                        },

                        cropAndUpload() {
                            if (!this.cropper) return;
                            this.cropUploading = true;
                            this.cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob((blob) => {
                                if (!blob) { this.cropUploading = false; alert('Gagal memotong gambar.'); return; }
                                const croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });
                                @this.upload('foto_ktp', croppedFile, () => {
                                    this.cropUploading = false;
                                    this.showCropperModal = false;
                                    this.ktpReady = true;
                                    if (this.cropper) this.cropper.destroy();
                                    @this.scanKtp();
                                }, (error) => {
                                    this.cropUploading = false;
                                    console.error('Upload error:', error);
                                    alert('Upload gagal. Periksa koneksi atau ukuran file.');
                                }, () => {});
                            }, 'image/jpeg', 0.85);
                        },

                        closeCropperModal() {
                            this.showCropperModal = false;
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = null;
                        },

                        resetKtp() {
                            this.ktpReady = false;
                            this.cropImageUrl = null;
                            if (this.$refs.ktpInput) this.$refs.ktpInput.value = '';
                        }
                    }
                }
            </script>
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
