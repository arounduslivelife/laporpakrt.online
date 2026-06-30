<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Buat Surat Pengantar</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Warga Pemohon</label>
                            <select class="form-select" wire:model="warga_id" required>
                                <option value="">-- Pilih Warga --</option>
                                @foreach($wargas as $warga)
                                    <option value="{{ $warga->id }}">{{ $warga->name }} (NIK: {{ $warga->nik }})</option>
                                @endforeach
                            </select>
                            @error('warga_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label>Jenis Surat</label>
                            <select class="form-select" wire:model="jenis" required>
                                <option value="pengantar_umum">Pengantar Umum</option>
                                <option value="keterangan_domisili">Keterangan Domisili</option>
                                <option value="keterangan_usaha">Keterangan Usaha</option>
                                <option value="keterangan_tidak_mampu">Keterangan Tidak Mampu</option>
                            </select>
                            @error('jenis') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label>Keperluan</label>
                            <textarea class="form-control" wire:model="keperluan" rows="3" required placeholder="Contoh: Pembuatan rekening bank, pendaftaran sekolah, dll"></textarea>
                            @error('keperluan') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Buat & Tandatangani Surat</button>
                        <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
