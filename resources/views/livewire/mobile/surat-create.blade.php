<div>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1">Buat Surat Baru</h5>
            <p class="text-muted small mb-0">Terbitkan dokumen pengantar</p>
        </div>
        <a href="{{ route('surat.index') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <form wire:submit.prevent="save">
        <div class="soft-card mb-4 p-4">
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted ms-1">Warga Pemohon</label>
                <select class="form-select form-select-lg" wire:model="warga_id" style="border-radius: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.95rem;" required>
                    <option value="">-- Pilih Warga --</option>
                    @foreach($wargas as $warga)
                        <option value="{{ $warga->id }}">{{ $warga->name }} ({{ substr($warga->nik, 0, 6) }}...)</option>
                    @endforeach
                </select>
                @error('warga_id') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted ms-1">Jenis Surat</label>
                <select class="form-select form-select-lg" wire:model="jenis" style="border-radius: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.95rem;" required>
                    <option value="pengantar_umum">Pengantar Umum</option>
                    <option value="keterangan_domisili">Keterangan Domisili</option>
                    <option value="keterangan_usaha">Keterangan Usaha</option>
                    <option value="keterangan_tidak_mampu">Keterangan Tidak Mampu</option>
                </select>
                @error('jenis') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted ms-1">Keperluan</label>
                <textarea class="form-control" wire:model="keperluan" rows="4" style="border-radius: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.95rem; resize: none;" required placeholder="Contoh: Pengurusan Rekening Bank..."></textarea>
                @error('keperluan') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
            </div>

        </div>

        <div class="mb-5 pb-5">
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold d-flex justify-content-center align-items-center gap-2" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; box-shadow: 0 8px 24px rgba(14,165,233,0.3);">
                <i class="bi bi-pen"></i>
                <span>Tandatangani & Terbitkan</span>
            </button>
            <p class="text-center text-muted small mt-3">Dokumen akan langsung memiliki Tanda Tangan Digital + QR Code</p>
        </div>
    </form>
</div>
