<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Test OCR KTP</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <i class="bi bi-upload"></i> Upload Foto KTP
                        </div>
                        <div class="card-body">
                            <!-- Include Cropper.js -->
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                            
                            <div x-data="{ cropper: null, imageUrl: null, uploading: false }" class="mb-3">
                                <input type="file" id="ktpImage" class="form-control" accept="image/*" capture="environment" 
                                    x-on:change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            imageUrl = URL.createObjectURL(file);
                                            if (cropper) cropper.destroy();
                                            setTimeout(() => {
                                                const imageElement = document.getElementById('cropperImage');
                                                cropper = new Cropper(imageElement, {
                                                    viewMode: 1,
                                                    autoCropArea: 0.9,
                                                });
                                            }, 100);
                                        }
                                    ">
                                @error('image') <span class="text-danger small">{{ $message }}</span> @enderror

                                <div x-show="imageUrl" class="mt-3" style="width: 100%; max-height: 400px; display:none;">
                                    <img id="cropperImage" :src="imageUrl" style="max-width: 100%;">
                                </div>

                                <div x-show="imageUrl" style="display:none;" class="mt-3">
                                    <button type="button" class="btn btn-success" x-bind:disabled="uploading"
                                        x-on:click="
                                            uploading = true;
                                            cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 }).toBlob((blob) => {
                                                if(!blob) { uploading = false; alert('Gagal memotong gambar.'); return; }
                                                const croppedFile = new File([blob], 'ktp_cropped.jpg', { type: 'image/jpeg' });
                                                @this.upload('image', croppedFile, (uploadedFilename) => {
                                                    uploading = false;
                                                    @this.scan();
                                                }, (error) => { uploading = false; console.error(error); alert('Upload gagal. Cek console.'); }, (event) => {});
                                            }, 'image/jpeg', 0.85);
                                        ">
                                        <span x-show="!uploading"><i class="bi bi-crop"></i> Crop & Scan KTP</span>
                                        <span x-show="uploading"><i class="bi bi-arrow-repeat spin"></i> Sedang Memproses...</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary" x-on:click="imageUrl = null; if(cropper) cropper.destroy(); @this.resetTest();">Reset</button>
                                </div>
                                <div wire:loading wire:target="scan" class="text-info mt-2"><i class="bi bi-arrow-repeat spin"></i> OCR Sedang Membaca Gambar...</div>
                            </div>

                            @if ($hasError)
                                <div class="alert alert-danger mt-3 mb-0">
                                    <strong>Gagal scan:</strong> {{ $errorMessage }}
                                    <hr class="my-2">
                                    <small>
                                        Pastikan Tesseract OCR sudah terinstall.<br>
                                        Windows: <code>C:\Program Files\Tesseract-OCR\tesseract.exe</code><br>
                                        Linux: <code>/usr/bin/tesseract</code>
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <i class="bi bi-card-text"></i> Hasil Pembacaan
                        </div>
                        <div class="card-body">
                            @if (empty($parsed) && ! $isScanning)
                                <p class="text-muted mb-0">Belum ada gambar yang di-scan.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            @foreach ([
                                                'nik' => 'NIK',
                                                'name' => 'Nama',
                                                'tempat_lahir' => 'Tempat Lahir',
                                                'tanggal_lahir' => 'Tanggal Lahir',
                                                'jenis_kelamin' => 'Jenis Kelamin',
                                                'alamat' => 'Alamat',
                                                'rt' => 'RT',
                                                'rw' => 'RW',
                                                'kelurahan' => 'Kelurahan/Desa',
                                                'kecamatan' => 'Kecamatan',
                                                'kabupaten' => 'Kabupaten/Kota',
                                                'provinsi' => 'Provinsi',
                                                'agama' => 'Agama',
                                                'status_perkawinan' => 'Status Perkawinan',
                                                'pekerjaan' => 'Pekerjaan',
                                                'kewarganegaraan' => 'Kewarganegaraan',
                                            ] as $key => $label)
                                                @if (isset($parsed[$key]) && $parsed[$key] !== null && $parsed[$key] !== '')
                                                    <tr>
                                                        <td class="text-muted" style="width: 35%;">{{ $label }}</td>
                                                        <td class="fw-medium">{{ $parsed[$key] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (! empty($rawText))
                        <div class="card">
                            <div class="card-header bg-light">
                                <i class="bi bi-file-text"></i> Teks Mentah OCR
                            </div>
                            <div class="card-body">
                                <pre class="mb-0" style="white-space: pre-wrap; font-size: 0.85rem;">{{ $rawText }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
