<div>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1">Surat & Dokumen</h5>
            <p class="text-muted small mb-0">Arsip surat pengantar</p>
        </div>
        <a href="{{ route('surat.create') }}" class="btn btn-primary btn-sm" style="border-radius: 12px;">
            <i class="bi bi-plus-lg"></i> Buat Surat
        </a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="input-group" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-radius: 12px; overflow: hidden;">
            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-0 bg-white ps-0" placeholder="Cari nama atau no surat..." wire:model.live.debounce.300ms="search">
        </div>
    </div>

    <!-- Letters List -->
    <div class="mb-5 pb-4">
        @forelse($surats as $surat)
            <div class="soft-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge {{ $surat->status == 'ditandatangani' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }} rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.7rem;">
                        <i class="bi {{ $surat->status == 'ditandatangani' ? 'bi-check-circle-fill' : 'bi-clock-fill' }} me-1"></i>
                        {{ ucfirst($surat->status) }}
                    </span>
                    <span class="text-muted" style="font-size: 0.7rem;">{{ $surat->created_at->format('d M Y') }}</span>
                </div>
                
                <h6 class="fw-bold text-dark mb-1">{{ $surat->warga->name }}</h6>
                <p class="text-primary fw-medium small mb-2">{{ ucfirst(str_replace('_', ' ', $surat->jenis)) }}</p>
                <p class="text-muted small mb-3" style="font-size: 0.75rem;"><i class="bi bi-hash"></i> {{ $surat->nomor_surat }}</p>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('surat.pdf', $surat->id) }}" target="_blank" class="btn btn-outline-primary btn-sm flex-grow-1" style="border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>
                    <button class="btn btn-outline-danger btn-sm px-3" style="border-radius: 10px;" wire:click="delete({{ $surat->id }})" wire:confirm="Hapus surat ini?">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-file-earmark-x text-primary fs-2"></i>
                </div>
                <p class="text-muted fw-medium">Belum ada dokumen surat</p>
                <a href="{{ route('surat.create') }}" class="btn btn-primary btn-sm px-4 mt-2" style="border-radius: 12px;">Buat Surat Pertama</a>
            </div>
        @endforelse
        
        <div class="mt-3">
            {{ $surats->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
