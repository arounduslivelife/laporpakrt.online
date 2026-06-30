<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Buku Tamu</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-success" wire:click="$toggle('showForm')" onclick="getLocation()">
                        <i class="bi bi-person-plus"></i> Input Tamu Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            @if($showForm)
            <div class="card mb-4 border-success">
                <div class="card-header text-bg-success">{{ $editMode ? 'Edit Data Tamu' : 'Form Pendataan Tamu Masuk' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Tamu</label>
                                <input type="text" class="form-control" wire:model="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>NIK (Opsional)</label>
                                <input type="text" class="form-control" wire:model="nik" maxlength="16">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Alamat (Opsional)</label>
                                <input type="text" class="form-control" wire:model="alamat">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label>Tujuan Kunjungan</label>
                                <input type="text" class="form-control" wire:model="tujuan" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Lama Kunjungan (Hari)</label>
                                <input type="number" class="form-control" wire:model="lama_kunjungan_hari" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto Wajah (Opsional)</label>
                                <input type="file" class="form-control" wire:model="file_wajah">
                                <div wire:loading wire:target="file_wajah" class="text-info small mt-1">Mengunggah...</div>
                                @if ($file_wajah)
                                    <img src="{{ $file_wajah->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @elseif ($editMode && $foto_wajah)
                                    <img src="{{ Storage::url($foto_wajah) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Foto KTP (Opsional)</label>
                                <input type="file" class="form-control" wire:model="file_ktp" accept="image/*">
                                <div wire:loading wire:target="file_ktp" class="text-info small mt-1">Mengunggah...</div>
                                @if ($file_ktp)
                                    <div class="mt-2">
                                        <img src="{{ $file_ktp->temporaryUrl() }}" class="img-thumbnail" style="max-height: 100px;">
                                        <button type="button" class="btn btn-info btn-sm ms-2" wire:click="scanKtp" wire:loading.attr="disabled" wire:target="scanKtp">
                                            <span wire:loading.remove wire:target="scanKtp"><i class="bi bi-upc-scan"></i> Scan KTP</span>
                                            <span wire:loading wire:target="scanKtp"><i class="bi bi-arrow-repeat spin"></i> Membaca...</span>
                                        </button>
                                    </div>
                                @elseif ($editMode && $foto_ktp)
                                    <img src="{{ Storage::url($foto_ktp) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
                                @endif
                                @error('file_ktp') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Geolocation Status -->
                        <div class="alert alert-info py-2" wire:ignore>
                            <i class="bi bi-geo-alt"></i> Mendapatkan koordinat lokasi... <span id="geo-status"></span>
                        </div>
                        
                        <!-- Hidden inputs for Livewire to bind -->
                        <input type="hidden" wire:model="lat" id="lat">
                        <input type="hidden" wire:model="lng" id="lng">

                        <button type="submit" class="btn btn-success mt-2">Simpan Laporan Tamu</button>
                        <button type="button" class="btn btn-secondary mt-2" wire:click="resetForm">Batal</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" class="form-control" placeholder="Cari Nama / Tujuan..." wire:model.live.debounce.300ms="search">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 Per Halaman</option>
                                <option value="25">25 Per Halaman</option>
                                <option value="50">50 Per Halaman</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Waktu Lapor</th>
                                <th>Nama Tamu</th>
                                <th>Tujuan</th>
                                <th>Lama</th>
                                <th>Lokasi Lapor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tamus as $tamu)
                            <tr>
                                <td>{{ $tamu->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $tamu->name }}</strong><br><small class="text-muted">{{ $tamu->nik ?? '-' }}{{ $tamu->alamat ? ' • ' . $tamu->alamat : '' }}</small></td>
                                <td>{{ $tamu->tujuan }}</td>
                                <td>{{ $tamu->lama_kunjungan_hari }} Hari</td>
                                <td>
                                    @if($tamu->lat && $tamu->lng)
                                        <a href="https://maps.google.com/?q={{ $tamu->lat }},{{ $tamu->lng }}" target="_blank" class="badge bg-info text-decoration-none">Lihat Peta</a>
                                    @else
                                        <span class="text-muted small">Tanpa Lokasi</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="edit({{ $tamu->id }})" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="delete({{ $tamu->id }})" class="btn btn-sm btn-danger" onclick="confirm('Yakin ingin menghapus data tamu ini?') || event.stopImmediatePropagation()"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada kunjungan tamu.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $tamus->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Geolocation Script -->
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                        @this.set('lat', position.coords.latitude);
                        @this.set('lng', position.coords.longitude);
                        document.getElementById('geo-status').innerText = "Terkunci: " + position.coords.latitude + ", " + position.coords.longitude;
                    }, 
                    function(error) {
                        document.getElementById('geo-status').innerText = "Gagal (Izin Ditolak / Error)";
                    }
                );
            } else {
                document.getElementById('geo-status').innerText = "Geolocation tidak didukung browser ini.";
            }
        }
    </script>
</div>
