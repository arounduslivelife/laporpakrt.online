<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <h3 class="mb-0">Halo, {{ auth()->user()->name }}</h3>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title fw-bold mb-0">Riwayat Pembayaran Kas</h5>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Periode</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kasTransactions as $kas)
                                    <tr>
                                        <td>{{ $kas->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $kas->periode_bulan }}</td>
                                        <td class="text-success">+ Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat iuran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title fw-bold mb-0">Status Surat Keterangan</h5>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Surat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surats as $surat)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis)) }}</td>
                                        <td>
                                            @if($surat->status == 'ditandatangani')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($surat->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($surat->status == 'ditandatangani')
                                                <a href="{{ route('surat.pdf', $surat->id) }}" target="_blank" class="btn btn-sm btn-outline-info">Unduh</a>
                                            @else
                                                <span class="text-muted small">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada permohonan surat.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
