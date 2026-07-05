<div>
    {{-- Cropper.js Assets --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        .ocr-stepper { display: flex; gap: 0; margin-bottom: 1.5rem; }
        .ocr-step { flex: 1; text-align: center; padding: .75rem .5rem; position: relative; background: #f0f2f5; color: #6c757d; font-weight: 600; font-size: .85rem; transition: all .4s ease; }
        .ocr-step:first-child { border-radius: .5rem 0 0 .5rem; }
        .ocr-step:last-child { border-radius: 0 .5rem .5rem 0; }
        .ocr-step.active { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: #fff; }
        .ocr-step.done { background: #198754; color: #fff; }
        .ocr-step .step-num { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: rgba(255,255,255,.2); font-size: .75rem; margin-right: .35rem; }
        .ocr-step.active .step-num { background: rgba(255,255,255,.35); }
        .ocr-step.done .step-num { background: rgba(255,255,255,.3); }

        .ocr-dropzone {
            border: 2.5px dashed #ced4da; border-radius: 1rem; padding: 2.5rem 1.5rem;
            text-align: center; cursor: pointer; transition: all .3s ease;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .ocr-dropzone:hover, .ocr-dropzone.dragover {
            border-color: #0d6efd; background: linear-gradient(135deg, #e7f1ff 0%, #d0e2ff 100%);
            transform: translateY(-2px); box-shadow: 0 8px 25px rgba(13,110,253,.15);
        }
        .ocr-dropzone .dropzone-icon { font-size: 3rem; color: #0d6efd; margin-bottom: .75rem; opacity: .7; }
        .ocr-dropzone:hover .dropzone-icon { opacity: 1; transform: scale(1.1); transition: .3s; }

        .cropper-area { border-radius: .75rem; overflow: hidden; border: 2px solid #dee2e6; background: #1a1a2e; }
        .cropper-area img { max-width: 100%; display: block; }

        .result-field { padding: .6rem .85rem; border-radius: .5rem; background: #f8f9fa; margin-bottom: .5rem; display: flex; align-items: center; transition: background .2s; }
        .result-field:hover { background: #e9ecef; }
        .result-field .field-label { color: #6c757d; font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; min-width: 120px; }
        .result-field .field-value { font-weight: 500; color: #212529; flex: 1; }

        .scan-progress { height: 4px; border-radius: 2px; overflow: hidden; background: #e9ecef; }
        .scan-progress-bar { height: 100%; background: linear-gradient(90deg, #0d6efd, #0dcaf0, #0d6efd); background-size: 200%; animation: shimmer 1.5s infinite; border-radius: 2px; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp .4s ease forwards; }
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="bi bi-upc-scan me-2"></i>Scanner KTP</h3>
                    <small class="text-muted">Ekstrak data identitas secara otomatis dari foto KTP</small>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid" x-data="ocrScanner()">

            {{-- Stepper --}}
            <div class="ocr-stepper">
                <div class="ocr-step" :class="{ 'active': step === 1, 'done': step > 1 }">
                    <span class="step-num" x-text="step > 1 ? '✓' : '1'"></span> Ambil Foto
                </div>
                <div class="ocr-step" :class="{ 'active': step === 2, 'done': step > 2 }">
                    <span class="step-num" x-text="step > 2 ? '✓' : '2'"></span> Potong Area
                </div>
                <div class="ocr-step" :class="{ 'active': step === 3, 'done': step > 3 }">
                    <span class="step-num">3</span> Lihat Hasil
                </div>
            </div>

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-6">
                    {{-- Step 1: Upload / Camera --}}
                    <div x-show="step === 1" x-transition class="fade-in-up">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="mb-3"><i class="bi bi-camera me-2 text-primary"></i>Langkah 1: Ambil Foto KTP</h5>
                                <div class="ocr-dropzone"
                                    :class="{ 'dragover': isDragging }"
                                    x-on:click="$refs.fileInput.click()"
                                    x-on:dragover.prevent="isDragging = true"
                                    x-on:dragleave.prevent="isDragging = false"
                                    x-on:drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0])">
                                    <div class="dropzone-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                    <h5 class="fw-bold mb-2">Seret foto KTP ke sini</h5>
                                    <p class="text-muted mb-3">atau klik untuk memilih file / buka kamera</p>
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-image me-1"></i> JPG, PNG &bull; Maks 8MB
                                    </span>
                                </div>
                                <input type="file" x-ref="fileInput" class="d-none" accept="image/*" capture="environment"
                                    x-on:change="handleFile($event.target.files[0])">
                                @error('image') <div class="alert alert-danger mt-3 py-2 mb-0"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Crop --}}
                    <div x-show="step === 2" x-transition class="fade-in-up">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="mb-3"><i class="bi bi-crop me-2 text-primary"></i>Langkah 2: Sesuaikan Area KTP</h5>
                                <p class="text-muted small mb-3">Seret kotak biru untuk membingkai area KTP. Buang latar belakang agar hasil OCR lebih akurat.</p>
                                <div class="cropper-area mb-3">
                                    <img id="cropperImageOcr" :src="imageUrl" style="max-width: 100%; display: block;">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary flex-fill" x-bind:disabled="uploading" x-on:click="cropAndScan()">
                                        <span x-show="!uploading"><i class="bi bi-upc-scan me-1"></i> Crop & Scan KTP</span>
                                        <span x-show="uploading"><i class="bi bi-arrow-repeat spin me-1"></i> Mengirim ke Server...</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" x-on:click="resetAll()">
                                        <i class="bi bi-arrow-counterclockwise"></i> Ulang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Scanning indicator --}}
                    <div x-show="step === 3" x-transition class="fade-in-up">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4 text-center" wire:loading wire:target="scan">
                                <div class="mb-3"><i class="bi bi-cpu text-primary" style="font-size: 2.5rem;"></i></div>
                                <h5 class="fw-bold">AI Sedang Membaca KTP...</h5>
                                <p class="text-muted small">Mengekstrak NIK, Nama, Alamat, dan data lainnya</p>
                                <div class="scan-progress mx-auto" style="max-width: 300px;">
                                    <div class="scan-progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>

                            @if ($hasError)
                            <div class="card-body p-4">
                                <div class="alert alert-danger mb-0">
                                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-1"></i> Gagal Scan</h6>
                                    <p class="mb-2">{{ $errorMessage }}</p>
                                    <hr>
                                    <p class="mb-0 small">Pastikan Tesseract OCR sudah terinstall di server VPS.</p>
                                </div>
                                <button class="btn btn-outline-primary mt-3" x-on:click="resetAll()"><i class="bi bi-arrow-counterclockwise me-1"></i> Coba Lagi</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Hasil --}}
                <div class="col-lg-6">
                    @if (empty($parsed) && !$isScanning && !$hasError)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            <div style="font-size: 4rem; opacity: .15;"><i class="bi bi-person-vcard"></i></div>
                            <h5 class="text-muted fw-light mt-2">Hasil OCR akan muncul di sini</h5>
                            <p class="text-muted small">Upload dan crop foto KTP terlebih dahulu</p>
                        </div>
                    </div>
                    @elseif(!empty($parsed))
                    <div class="card border-0 shadow-sm fade-in-up">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Hasil Pembacaan</h5>
                                <span class="badge bg-success-subtle text-success px-3 py-2">Berhasil</span>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-4">
                            @foreach ([
                                'nik' => ['NIK', 'bi-hash'],
                                'name' => ['Nama', 'bi-person'],
                                'tempat_lahir' => ['Tempat Lahir', 'bi-geo-alt'],
                                'tanggal_lahir' => ['Tanggal Lahir', 'bi-calendar'],
                                'jenis_kelamin' => ['Jenis Kelamin', 'bi-gender-ambiguous'],
                                'alamat' => ['Alamat', 'bi-house'],
                                'rt' => ['RT', 'bi-signpost'],
                                'rw' => ['RW', 'bi-signpost-2'],
                                'kelurahan' => ['Kelurahan/Desa', 'bi-building'],
                                'kecamatan' => ['Kecamatan', 'bi-buildings'],
                                'kabupaten' => ['Kabupaten/Kota', 'bi-pin-map'],
                                'provinsi' => ['Provinsi', 'bi-map'],
                                'agama' => ['Agama', 'bi-book'],
                                'status_perkawinan' => ['Status Perkawinan', 'bi-heart'],
                                'pekerjaan' => ['Pekerjaan', 'bi-briefcase'],
                                'kewarganegaraan' => ['Kewarganegaraan', 'bi-flag'],
                            ] as $key => [$label, $icon])
                                @if (isset($parsed[$key]) && $parsed[$key] !== null && $parsed[$key] !== '')
                                    <div class="result-field">
                                        <span class="field-label"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</span>
                                        <span class="field-value">{{ $parsed[$key] }}</span>
                                    </div>
                                @endif
                            @endforeach

                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-outline-primary flex-fill" x-on:click="resetAll()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Scan KTP Lain
                                </button>
                            </div>
                        </div>
                    </div>

                    @if (!empty($rawText))
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between" 
                             style="cursor:pointer;" 
                             x-data="{ open: false }" x-on:click="open = !open">
                            <span><i class="bi bi-code-slash me-2"></i>Teks Mentah OCR</span>
                            <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                        </div>
                        <div class="card-body" x-data="{ open: false }" x-show="open" x-transition>
                            <pre class="mb-0 p-3 bg-dark text-light rounded" style="font-size: .8rem; max-height: 250px; overflow-y: auto;">{{ $rawText }}</pre>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function ocrScanner() {
            return {
                step: 1,
                imageUrl: null,
                cropper: null,
                uploading: false,
                isDragging: false,

                handleFile(file) {
                    if (!file) return;
                    if (file.size > 8 * 1024 * 1024) {
                        alert('File terlalu besar. Maksimal 8MB.');
                        return;
                    }
                    if (!file.type.startsWith('image/')) {
                        alert('Hanya file gambar yang diperbolehkan.');
                        return;
                    }
                    this.imageUrl = URL.createObjectURL(file);
                    this.step = 2;

                    this.$nextTick(() => {
                        setTimeout(() => {
                            const img = document.getElementById('cropperImageOcr');
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new Cropper(img, {
                                viewMode: 1,
                                autoCropArea: 0.85,
                                responsive: true,
                                background: true,
                            });
                        }, 200);
                    });
                },

                cropAndScan() {
                    if (!this.cropper) return;
                    this.uploading = true;
                    this.cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob((blob) => {
                        if (!blob) { this.uploading = false; alert('Gagal memotong gambar.'); return; }
                        const croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });
                        @this.upload('image', croppedFile, () => {
                            this.uploading = false;
                            this.step = 3;
                            @this.scan();
                        }, (error) => {
                            this.uploading = false;
                            console.error('Upload error:', error);
                            alert('Upload gagal. Periksa koneksi atau ukuran file.');
                        }, () => {});
                    }, 'image/jpeg', 0.85);
                },

                resetAll() {
                    if (this.cropper) this.cropper.destroy();
                    this.cropper = null;
                    this.imageUrl = null;
                    this.uploading = false;
                    this.step = 1;
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                    @this.resetTest();
                }
            }
        }
    </script>
</div>
