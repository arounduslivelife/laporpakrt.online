<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Pengaturan Sistem</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if ($is_saved)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> Pengaturan berhasil disimpan!
                    <button type="button" class="btn-close" wire:click="$set('is_saved', false)"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-cpu me-2"></i> Konfigurasi AI (Gemini)</h5>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="save">
                                <div class="mb-3">
                                    <label for="gemini_api_key" class="form-label">Google Gemini API Key</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="password" class="form-control" id="gemini_api_key" wire:model="gemini_api_key" 
                                            placeholder="AIzaSyB........................" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="const input = document.getElementById('gemini_api_key'); input.type = input.type === 'password' ? 'text' : 'password';">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        API Key ini digunakan untuk fitur pemindaian KTP Otomatis (OCR). Dapatkan kunci gratis di <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>.
                                    </div>
                                    @error('gemini_api_key') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <div wire:loading wire:target="save">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </div>
                                    <span wire:loading.remove wire:target="save">
                                        <i class="bi bi-save me-1"></i> Simpan Pengaturan
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
