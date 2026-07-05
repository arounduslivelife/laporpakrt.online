<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Pengaturan Sistem</h5>
        <p class="text-muted small">Konfigurasi API & Integrasi</p>
    </div>

    @if ($is_saved)
        <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center" role="alert" style="border-radius: 16px; background-color: #dcfce7; color: #166534;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span class="small fw-medium">Pengaturan berhasil disimpan!</span>
            <button type="button" class="btn-close" wire:click="$set('is_saved', false)" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
        </div>
    @endif

    <div class="soft-card p-4 mb-4">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                <i class="bi bi-cpu text-primary fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">Google Gemini AI</h6>
                <p class="text-muted small mb-0">Pemindaian KTP Otomatis (OCR)</p>
            </div>
        </div>

        <form wire:submit.prevent="save">
            <div class="mb-4 mt-3">
                <label class="form-label small fw-bold text-muted ms-1">API Key</label>
                <div class="input-group" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <span class="input-group-text bg-white border-0 text-muted px-3"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control border-0 bg-white ps-0" id="gemini_api_key_mobile" wire:model="gemini_api_key" placeholder="AIzaSyB..." required style="font-size: 0.95rem;">
                    <button class="btn btn-white bg-white border-0 text-muted px-3" type="button" onclick="const input = document.getElementById('gemini_api_key_mobile'); input.type = input.type === 'password' ? 'text' : 'password';">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="form-text mt-2 small text-muted">
                    Dapatkan API Key secara gratis di <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-primary fw-medium text-decoration-none">Google AI Studio</a>.
                </div>
                @error('gemini_api_key') <span class="text-danger small mt-1 d-block ms-1">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold d-flex justify-content-center align-items-center gap-2" style="border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; box-shadow: 0 8px 24px rgba(14,165,233,0.3);">
                <div wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Menyimpan...
                </div>
                <div wire:loading.remove wire:target="save">
                    <i class="bi bi-save me-1"></i> Simpan Pengaturan
                </div>
            </button>
        </form>
    </div>
</div>
