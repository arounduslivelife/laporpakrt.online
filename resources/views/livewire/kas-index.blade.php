<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manajemen Kas RT</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-primary" wire:click="$toggle('showForm')"><i class="bi bi-plus-lg"></i> Tambah Transaksi</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box text-bg-success">
                        <span class="info-box-icon"><i class="bi bi-wallet2"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold">Total Saldo Kas</span>
                            <span class="info-box-number fs-4">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($showForm)
            <div class="card mb-4">
                <div class="card-header bg-light">Input Transaksi Kas</div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Tipe Transaksi</label>
                                <select class="form-select" wire:model.live="tipe" required>
                                    <option value="pemasukan">Pemasukan</option>
                                    <option value="pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Jumlah (Rp)</label>
                                <input type="number" class="form-control" wire:model="jumlah" required min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Kategori</label>
                                <input type="text" class="form-control" wire:model="kategori" required placeholder="Cth: Iuran Bulanan / Konsumsi">
                            </div>
                            
                            @if($tipe == 'pemasukan')
                            <div class="col-md-6 mb-3">
                                <label>Warga Pembayar (Opsional)</label>
                                <select class="form-select" wire:model="warga_id">
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($wargas as $warga)
                                        <option value="{{ $warga->id }}">{{ $warga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Periode Pembayaran (Jika Iuran)</label>
                                <input type="month" class="form-control" wire:model="periode_bulan">
                            </div>
                            @endif

                            <div class="col-md-12 mb-3">
                                <label>Keterangan Tambahan</label>
                                <textarea class="form-control" wire:model="keterangan" rows="2"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Batal</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" class="form-control" placeholder="Cari Kategori/Keterangan..." wire:model.live.debounce.300ms="search">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" wire:model.live="filterTipe">
                                <option value="">Semua Tipe</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $trx->kategori }}
                                    @if($trx->warga)
                                        <br><small class="text-muted">Dari: {{ $trx->warga->name }} ({{ $trx->periode_bulan }})</small>
                                    @endif
                                </td>
                                <td>{{ $trx->keterangan }}</td>
                                <td>
                                    @if($trx->tipe == 'pemasukan')
                                        <span class="text-success">+ Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-danger">- Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $trx->id }})" onclick="return confirm('Hapus transaksi ini?')"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi kas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
