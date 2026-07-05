<div>
    <style>
        .ocr-hero { background: linear-gradient(135deg, #1e3a5f 0%, #0d47a1 100%); color: #fff; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; }
        .ocr-upload-zone {
            border: 3px dashed #42a5f5; border-radius: 1rem; padding: 3rem 1.5rem;
            text-align: center; cursor: pointer; transition: all .3s ease;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }
        .ocr-upload-zone:hover { border-color: #1565c0; background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(21,101,192,.2); }
        .ocr-upload-zone .zone-icon { font-size: 3.5rem; color: #1565c0; }
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

        /* === WEBCAM OVERLAY === */
        .webcam-overlay{position:fixed;inset:0;background:#000;z-index:9999;display:none;flex-direction:column}
        .webcam-header{padding:1rem;background:#1a1a2e;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .webcam-body{flex:1;position:relative;overflow:hidden;background:#000;display:flex;align-items:center;justify-content:center}
        .webcam-body video{min-width:100%;min-height:100%;object-fit:cover;position:absolute;inset:0;z-index:1}
        .ktp-guide{position:absolute;width:90%;aspect-ratio:1.58;border:2px solid rgba(255,255,255,0.7);box-shadow:0 0 0 3000px rgba(0,0,0,0.6);z-index:10;border-radius:8px}
        @media (min-aspect-ratio: 1/1) { .ktp-guide { width: 50%; } }
        .guide-corner{position:absolute;width:30px;height:30px;border-color:#42a5f5;border-style:solid;z-index:11}
        .guide-corner.top-left{top:-2px;left:-2px;border-width:4px 0 0 4px;border-top-left-radius:8px}
        .guide-corner.top-right{top:-2px;right:-2px;border-width:4px 4px 0 0;border-top-right-radius:8px}
        .guide-corner.bottom-left{bottom:-2px;left:-2px;border-width:0 0 4px 4px;border-bottom-left-radius:8px}
        .guide-corner.bottom-right{bottom:-2px;right:-2px;border-width:0 4px 4px 0;border-bottom-right-radius:8px}
        .guide-text{position:absolute;top:-30px;left:0;right:0;text-align:center;color:#fff;font-size:14px;font-weight:600;text-shadow:1px 1px 3px #000}
        .webcam-footer{padding:1.5rem 1rem;background:#1a1a2e;display:flex;gap:1rem;justify-content:center;flex-shrink:0;z-index:10}
        .webcam-footer .btn{min-width:130px;padding:.8rem 1.5rem;font-weight:600;border-radius:2rem;font-size:1rem}
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
                        <p class="mb-0 opacity-75">Buka kamera dan arahkan ke KTP. Gambar otomatis di-crop dan diproses AI. Sangat cepat dan mudah.</p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="bi bi-person-vcard" style="font-size: 4rem; opacity: .3;"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 mb-4">
                    <div id="ocrUploadArea">
                        <div class="ocr-upload-zone" onclick="window._ocrOpenCam()">
                            <div class="zone-icon"><i class="bi bi-camera"></i></div>
                            <h5 class="fw-bold mt-2 mb-1">Buka Kamera Pemindai</h5>
                            <p class="text-muted mb-2">Menggunakan WebRTC in-app camera</p>
                        </div>
                    </div>

                    <div id="ocrStatusArea" style="display:none;" class="fade-up">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-cpu text-primary spin" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold mt-3">AI Sedang Membaca KTP...</h5>
                                <p class="text-muted small mb-3">Mengekstrak NIK, Nama, Alamat, dan data lainnya</p>
                                <div class="mt-3 mb-4">
                                    <img id="ktp-preview-img" src="" class="img-thumbnail rounded" style="max-height: 120px; display: none; margin: 0 auto;">
                                </div>
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
                            <p class="text-muted small">Ambil gambar KTP dengan kamera pemindai terlebih dahulu</p>
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
    {{-- WEBCAM OVERLAY --}}
    {{-- ============================================================ --}}
    <div id="ocrCamOverlay" class="webcam-overlay">
        <div class="webcam-header">
            <h6 class="mb-0"><i class="bi bi-camera me-2"></i>Scan KTP</h6>
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window._ocrCloseCam()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="webcam-body">
            <video id="ocrVideo" autoplay playsinline></video>
            <div id="ocrGuide" class="ktp-guide">
                <div class="guide-text">Posisikan KTP di dalam kotak ini</div>
                <div class="guide-corner top-left"></div>
                <div class="guide-corner top-right"></div>
                <div class="guide-corner bottom-left"></div>
                <div class="guide-corner bottom-right"></div>
            </div>
        </div>
        <div class="webcam-footer">
            <button type="button" class="btn btn-outline-light" onclick="window._ocrCloseCam()">Batal</button>
            <button type="button" class="btn btn-primary" id="btnTakeOcrPic" onclick="window._ocrTakePic()">
                <i class="bi bi-camera-fill me-1"></i> Ambil Foto
            </button>
        </div>
    </div>

    <canvas id="ocrCanvas" style="display:none;"></canvas>

    <script>
        (function() {
            var stream = null;

            window._ocrOpenCam = function() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Browser Anda tidak mendukung fitur Web Camera. Gunakan Chrome atau Safari terbaru.');
                    return;
                }
                document.getElementById('ocrCamOverlay').style.display = 'flex';
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
                    audio: false
                }).then(function(s) {
                    stream = s;
                    var video = document.getElementById('ocrVideo');
                    video.srcObject = stream;
                    video.play();
                }).catch(function(err) {
                    console.error(err);
                    alert('Gagal mengakses kamera: ' + err.message);
                    window._ocrCloseCam();
                });
            };

            window._ocrCloseCam = function() {
                document.getElementById('ocrCamOverlay').style.display = 'none';
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
            };

            window._ocrTakePic = function() {
                var btn = document.getElementById('btnTakeOcrPic');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Memproses...';

                var video = document.getElementById('ocrVideo');
                var guide = document.getElementById('ocrGuide');
                var canvas = document.getElementById('ocrCanvas');
                var ctx = canvas.getContext('2d');

                var vw = video.videoWidth;
                var vh = video.videoHeight;
                var cw = video.clientWidth;
                var ch = video.clientHeight;
                var vRatio = vw / vh;
                var cRatio = cw / ch;

                var renderW, renderH;
                if (cRatio > vRatio) { renderW = cw; renderH = cw / vRatio; } 
                else { renderH = ch; renderW = ch * vRatio; }
                
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

                canvas.width = cropW;
                canvas.height = cropH;
                ctx.drawImage(video, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);

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
                    component.upload('image', file, function() {
                        window._ocrCloseCam();
                        btn.disabled = false; btn.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Ambil Foto';
                        
                        document.getElementById('ocrUploadArea').style.display = 'none';
                        document.getElementById('ocrStatusArea').style.display = 'block';
                        
                        component.call('scan');
                    }, function(error) {
                        btn.disabled = false; btn.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Ambil Foto';
                        alert('Upload gagal. Cek koneksi.');
                    }, function() {});
                }, 'image/jpeg', 0.9);
            };

            window._ocrReset = function() {
                var dz = document.getElementById('ocrUploadArea');
                var sc = document.getElementById('ocrStatusArea');
                var imgPreview = document.getElementById('ktp-preview-img');
                if (dz) dz.style.display = 'block';
                if (sc) sc.style.display = 'none';
                if (imgPreview) { imgPreview.src = ''; imgPreview.style.display = 'none'; }
                var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                component.call('resetTest');
            };
        })();
    </script>
</div>
