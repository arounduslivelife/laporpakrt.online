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
                            <form wire:submit.prevent="scan">
                                <div class="mb-3">
                                    <input type="file" class="form-control" wire:model="image" accept="image/*" required>
                                    <div wire:loading wire:target="image" class="text-info small mt-1">Mengunggah...</div>
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                @if ($image)
                                    <div class="mb-3">
                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid img-thumbnail" style="max-height: 220px;">
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="scan">
                                    <span wire:loading.remove wire:target="scan"><i class="bi bi-upc-scan"></i> Scan KTP</span>
                                    <span wire:loading wire:target="scan"><i class="bi bi-arrow-repeat spin"></i> Membaca...</span>
                                </button>
                                <button type="button" class="btn btn-secondary" wire:click="resetTest">Reset</button>
                            </form>

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
