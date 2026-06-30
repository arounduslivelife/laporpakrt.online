<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dokumen & Surat</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('surat.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Surat</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Cari Nomor Surat / Nama Warga..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Nama Warga</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $surat)
                            <tr>
                                <td>{{ $surat->nomor_surat }}</td>
                                <td>{{ $surat->warga->name }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis)) }}</td>
                                <td>
                                    @if($surat->status == 'ditandatangani')
                                        <span class="badge bg-success">Ditandatangani</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($surat->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('surat.pdf', $surat->id) }}" target="_blank" class="btn btn-sm btn-info text-white"><i class="bi bi-printer"></i> Cetak</a>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $surat->id }})" onclick="return confirm('Hapus surat ini?')"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada data surat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $surats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
