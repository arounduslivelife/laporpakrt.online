<div>
    {{-- Assets - SELALU di-load --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .ocr-hero { background: linear-gradient(135deg, #1e3a5f 0%, #0d47a1 100%); color: #fff; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; }
        .ocr-upload-zone {
            border: 3px dashed #42a5f5; border-radius: 1rem; padding: 3rem 1.5rem;
            text-align: center; cursor: pointer; transition: all .3s ease;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }
        .ocr-upload-zone:hover { border-color: #1565c0; background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(21,101,192,.2); }
        .ocr-upload-zone .zone-icon { font-size: 3.5rem; color: #1565c0; }
        .crop-overlay{position:fixed;inset:0;background:rgba(0,0,0,.95);z-index:9999;display:none;flex-direction:column}
        .crop-header{padding:.75rem 1rem;background:#1e1e2e;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
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
        .result-item { display: flex; align-items: center; padding: .65rem 1rem; border-bottom: 1px solid #f0f0f0; transition: background .2s; }
        .result-item:last-child { border-bottom: none; }
        .result-item:hover { background: #f8f9fa; }
        .result-item .r-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .85rem; margin-right: .75rem; flex-shrink: 0; }
        .result-item .r-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; color: #9e9e9e; font-weight: 600; }
        .result-item .r-value { font-weight: 500; color: #212121; }
        .scan-shimmer { height: 5px; border-radius: 3px; overflow: hidden; background: #e0e0e0; }
        .scan-shimmer-bar { height: 100%; width: 100%; background: linear-gradient(90deg, #1565c0, #42a5f5, #1565c0); background-size: 200%; animation: shimmer 1.2s infinite linear; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp .5s ease forwards; }
        .spin { animation: spin-anim 1s linear infinite; }
        @keyframes spin-anim { from{transform:rotate(0)} to{transform:rotate(360deg)} }
    </style>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="bi bi-upc-scan me-2"></i>Scanner KTP</h3>
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
        <div class="container-fluid">

            <div class="ocr-hero">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3><i class="bi bi-cpu me-2"></i>Ekstrak Data KTP Otomatis</h3>
                        <p class="mb-0 opacity-75">Upload atau foto KTP, lalu potong (crop) area KTP. AI akan membaca NIK, Nama, Alamat, dan data lainnya secara otomatis.</p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="bi bi-person-vcard" style="font-size: 4rem; opacity: .3;"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 mb-4">
                    <div id="ocrUploadArea">
                        <div class="ocr-upload-zone" onclick="document.getElementById('ocrFileInput').click()">
                            <div class="zone-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <h5 class="fw-bold mt-2 mb-1">Klik untuk Foto / Upload KTP</h5>
                            <p class="text-muted mb-2">Kamera akan terbuka otomatis di HP</p>
                            <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                                <i class="bi bi-image me-1"></i> JPG, PNG &bull; Maks 8MB
                            </span>
                        </div>
                        <input type="file" id="ocrFileInput" accept="image/*" capture="environment" class="d-none"
                            onchange="window._ocrOpenCropper(this)">
                    </div>

                    <div id="ocrStatusArea" style="display:none;" class="fade-up">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-cpu text-primary spin" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold mt-3">AI Sedang Membaca KTP...</h5>
                                <p class="text-muted small mb-3">Mengekstrak NIK, Nama, Alamat, dan data lainnya</p>
                                <div class="scan-shimmer mx-auto" style="max-width: 250px;">
                                    <div class="scan-shimmer-bar"></div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary w-100 mt-3" onclick="window._ocrReset()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Scan KTP Lain
                        </button>
                    </div>

                    @if ($hasError)
                    <div class="card border-0 shadow-sm mt-3 fade-up">
                        <div class="card-body">
                            <div class="alert alert-danger mb-0">
                                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-1"></i> Gagal Scan</h6>
                                <p class="mb-2">{{ $errorMessage }}</p>
                                <hr>
                                <p class="mb-0 small">Pastikan Tesseract OCR sudah terinstall di server.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-7 mb-4">
                    @if (empty($parsed) && !$isScanning && !$hasError)
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-5 text-center">
                            <div style="font-size: 5rem; opacity: .08;"><i class="bi bi-person-vcard"></i></div>
                            <h5 class="text-muted fw-light mt-2">Hasil OCR akan muncul di sini</h5>
                            <p class="text-muted small">Upload dan crop foto KTP terlebih dahulu</p>
                        </div>
                    </div>
                    @elseif(!empty($parsed))
                    <div class="card border-0 shadow-sm fade-up">
                        <div class="card-header bg-white py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Hasil Pembacaan</h5>
                                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="bi bi-patch-check me-1"></i> Berhasil</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $fields = [
                                    'nik' => ['NIK', 'bi-hash', 'bg-primary-subtle text-primary'],
                                    'name' => ['Nama', 'bi-person', 'bg-info-subtle text-info'],
                                    'tempat_lahir' => ['Tempat Lahir', 'bi-geo-alt', 'bg-warning-subtle text-warning'],
                                    'tanggal_lahir' => ['Tanggal Lahir', 'bi-calendar', 'bg-danger-subtle text-danger'],
                                    'jenis_kelamin' => ['Jenis Kelamin', 'bi-gender-ambiguous', 'bg-secondary-subtle text-secondary'],
                                    'alamat' => ['Alamat', 'bi-house', 'bg-success-subtle text-success'],
                                    'rt' => ['RT', 'bi-signpost', 'bg-primary-subtle text-primary'],
                                    'rw' => ['RW', 'bi-signpost-2', 'bg-primary-subtle text-primary'],
                                    'kelurahan' => ['Kelurahan/Desa', 'bi-building', 'bg-info-subtle text-info'],
                                    'kecamatan' => ['Kecamatan', 'bi-buildings', 'bg-info-subtle text-info'],
                                    'kabupaten' => ['Kabupaten/Kota', 'bi-pin-map', 'bg-warning-subtle text-warning'],
                                    'provinsi' => ['Provinsi', 'bi-map', 'bg-danger-subtle text-danger'],
                                    'agama' => ['Agama', 'bi-book', 'bg-secondary-subtle text-secondary'],
                                    'status_perkawinan' => ['Status', 'bi-heart', 'bg-danger-subtle text-danger'],
                                    'pekerjaan' => ['Pekerjaan', 'bi-briefcase', 'bg-success-subtle text-success'],
                                    'kewarganegaraan' => ['WNI', 'bi-flag', 'bg-primary-subtle text-primary'],
                                ];
                            @endphp
                            @foreach ($fields as $key => [$label, $icon, $color])
                                @if (isset($parsed[$key]) && $parsed[$key] !== null && $parsed[$key] !== '')
                                <div class="result-item">
                                    <div class="r-icon {{ $color }}"><i class="bi {{ $icon }}"></i></div>
                                    <div>
                                        <div class="r-label">{{ $label }}</div>
                                        <div class="r-value">{{ $parsed[$key] }}</div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if (!empty($rawText))
                    <details class="mt-3">
                        <summary class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-code-slash me-1"></i> Lihat Teks Mentah OCR</summary>
                        <pre class="mt-2 p-3 bg-dark text-light rounded" style="font-size: .8rem; max-height: 200px; overflow-y: auto;">{{ $rawText }}</pre>
                    </details>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Cropper Overlay & Scripts - DI LUAR conditional blocks --}}
    {{-- ============================================================ --}}
    <div id="ocrCropOverlay" class="crop-overlay">
        <div class="crop-header">
            <h6 class="mb-0"><i class="bi bi-crop me-2"></i>Potong Area KTP</h6>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window._ocrCloseCropper()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="px-3 py-2 text-center" style="background:#252540; color:#adb5bd;">
            <small><i class="bi bi-info-circle me-1"></i>Seret & perbesar kotak biru agar membingkai KTP fisik saja.</small>
        </div>
        <div class="crop-body">
            <img id="ocrCropImage" src="" alt="KTP">
        </div>
        <div class="crop-footer">
            <button type="button" class="btn btn-outline-light px-4" onclick="window._ocrCloseCropper()">Batal</button>
            <button type="button" class="btn btn-primary px-4" id="btnOcrCropScan" onclick="window._ocrDoCrop()">
                <i class="bi bi-upc-scan me-1"></i> Crop & Scan KTP
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        (function() {
            var cropper = null;

            window._ocrOpenCropper = function(input) {
                var file = input.files[0];
                if (!file) return;
                if (file.size > 8 * 1024 * 1024) { alert('File terlalu besar. Maks 8MB.'); input.value = ''; return; }

                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('ocrCropImage');
                    img.src = e.target.result;
                    document.getElementById('ocrCropOverlay').style.display = 'flex';

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

            window._ocrCloseCropper = function() {
                document.getElementById('ocrCropOverlay').style.display = 'none';
                if (cropper) { cropper.destroy(); cropper = null; }
            };

            window._ocrDoCrop = function() {
                if (!cropper) return;
                var btn = document.getElementById('btnOcrCropScan');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Memproses...';

                cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob(function(blob) {
                    if (!blob) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan KTP';
                        alert('Gagal crop.');
                        return;
                    }
                    var croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });

                    var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                    component.upload('image', croppedFile, function() {
                        window._ocrCloseCropper();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan KTP';

                        document.getElementById('ocrUploadArea').style.display = 'none';
                        document.getElementById('ocrStatusArea').style.display = 'block';

                        component.call('scan');
                    }, function(error) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-upc-scan me-1"></i> Crop & Scan KTP';
                        console.error(error);
                        alert('Upload gagal. Cek koneksi internet.');
                    }, function() {});
                }, 'image/jpeg', 0.85);
            };

            window._ocrReset = function() {
                document.getElementById('ocrUploadArea').style.display = 'block';
                document.getElementById('ocrStatusArea').style.display = 'none';
                document.getElementById('ocrFileInput').value = '';
                var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                component.call('resetTest');
            };
        })();
    </script>
</div>
