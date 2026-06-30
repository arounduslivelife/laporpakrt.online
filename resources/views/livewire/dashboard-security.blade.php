<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <h3 class="mb-0">Dashboard Security</h3>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box text-bg-warning">
                        <span class="info-box-icon"><i class="bi bi-person-badge"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold">Tamu Hari Ini</span>
                            <span class="info-box-number fs-4">{{ $tamuHariIni }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-primary">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">Selamat Bertugas, {{ auth()->user()->name }}!</h4>
                            <p class="text-muted">Pantau keamanan dan catat setiap tamu yang masuk/keluar.</p>
                            <a href="{{ route('tamu.index') }}" class="btn btn-primary mt-2 px-4 py-2">Buka Buku Tamu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
