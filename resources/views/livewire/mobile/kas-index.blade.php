<div>
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Kas RT</h5>
        <p class="text-muted small">Catatan Pemasukan & Pengeluaran</p>
    </div>

    <!-- Saldo Box -->
    <div class="soft-card mb-4" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
        <p class="mb-1 opacity-75 small fw-medium">Total Saldo Saat Ini</p>
        <h2 class="fw-bold mb-0">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
    </div>

    <!-- Filter & Search -->
    <div class="row g-2 mb-4">
        <div class="col-8">
            <input type="text" class="form-control" placeholder="Cari..." wire:model.live.debounce.300ms="search" style="border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        </div>
        <div class="col-4">
            <select class="form-select" wire:model.live="filterTipe" style="border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <option value="">Semua</option>
                <option value="pemasukan">In</option>
                <option value="pengeluaran">Out</option>
            </select>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="mb-5 pb-4">
        @forelse($transactions as $trx)
            <div class="soft-card p-3 mb-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: {{ $trx->tipe == 'pemasukan' ? '#dcfce7' : '#fee2e2' }}; color: {{ $trx->tipe == 'pemasukan' ? '#16a34a' : '#dc2626' }};">
                        <i class="bi bi-arrow-{{ $trx->tipe == 'pemasukan' ? 'down-left' : 'up-right' }} fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">{{ $trx->kategori }}</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">{{ $trx->created_at->format('d M Y') }}</span>
                        @if($trx->warga)
                            <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-person"></i> {{ $trx->warga->name }}</div>
                        @endif
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold d-block {{ $trx->tipe == 'pemasukan' ? 'text-success' : 'text-danger' }}" style="font-size: 0.9rem;">
                        {{ $trx->tipe == 'pemasukan' ? '+' : '-' }}Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                    </span>
                    <button class="btn btn-link p-0 text-danger" wire:click="delete({{ $trx->id }})" wire:confirm="Hapus transaksi ini?">
                        <i class="bi bi-trash small"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-receipt text-muted fs-1 mb-2 opacity-50"></i>
                <p class="text-muted small">Belum ada transaksi</p>
            </div>
        @endforelse
        
        <div class="mt-3">
            {{ $transactions->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- FAB -->
    <button class="fab" wire:click="$toggle('showForm')">
        <i class="bi bi-plus"></i>
    </button>

    <!-- Bottom Sheet Form for Add Transaction -->
    @if($showForm)
    <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:click="$set('showForm', false)"></div>
    <div class="position-fixed bottom-0 start-0 w-100 bg-white" style="z-index: 1060; border-radius: 24px 24px 0 0; max-height: 85vh; overflow-y: auto; padding: 24px; transition: transform 0.3s ease-out;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Tambah Transaksi</h6>
            <button class="btn-close" wire:click="$set('showForm', false)"></button>
        </div>
        
        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Tipe</label>
                <select class="form-select form-select-lg" wire:model.live="tipe" style="border-radius: 12px; font-size: 0.95rem;">
                    <option value="pemasukan">Pemasukan (+)</option>
                    <option value="pengeluaran">Pengeluaran (-)</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Nominal (Rp)</label>
                <input type="number" class="form-control form-control-lg" wire:model="jumlah" style="border-radius: 12px; font-size: 0.95rem;" placeholder="10000" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Kategori</label>
                <input type="text" class="form-control form-control-lg" wire:model="kategori" style="border-radius: 12px; font-size: 0.95rem;" placeholder="Cth: Iuran Bulanan" required>
            </div>
            
            @if($tipe == 'pemasukan')
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Dari Warga (Opsional)</label>
                <select class="form-select form-select-lg" wire:model="warga_id" style="border-radius: 12px; font-size: 0.95rem;">
                    <option value="">-- Pilih --</option>
                    @foreach($wargas as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Periode (Opsional)</label>
                <input type="month" class="form-control form-control-lg" wire:model="periode_bulan" style="border-radius: 12px; font-size: 0.95rem;">
            </div>
            @endif
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Keterangan</label>
                <textarea class="form-control" wire:model="keterangan" rows="2" style="border-radius: 12px; font-size: 0.95rem;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4" style="border-radius: 16px;">
                Simpan Transaksi
            </button>
        </form>
    </div>
    @endif
</div>
