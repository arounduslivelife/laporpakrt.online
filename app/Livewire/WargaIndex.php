<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Warga;
use App\Services\KtpScannerService;

class WargaIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $nik, $no_kk, $name, $alamat, $status_domisili = 'Tetap', $jenis_kelamin, $tempat_lahir, $tanggal_lahir;
    public $foto_wajah, $foto_ktp;
    public $foto_path, $ktp_path;
    public $showForm = false;

    public function mount()
    {
        abort_if(auth()->user()->role != 'rt_admin', 403);
    }
    public $editMode = false;
    public $editId = null;

    public $search = '';
    public $filterDomisili = '';
    public $perPage = 10;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterDomisili() { $this->resetPage(); }

    public function edit($id)
    {
        $warga = Warga::where('rt_id', auth()->user()->rt_id)->findOrFail($id);
        $this->fill($warga->only(['nik', 'no_kk', 'name', 'alamat', 'status_domisili', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'foto_path', 'ktp_path']));
        $this->editMode = true;
        $this->editId = $id;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Warga::where('rt_id', auth()->user()->rt_id)->findOrFail($id)->delete();
    }

    public function resetForm()
    {
        $this->reset(['nik', 'no_kk', 'name', 'alamat', 'status_domisili', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'editMode', 'editId', 'showForm', 'foto_wajah', 'foto_ktp', 'foto_path', 'ktp_path']);
        $this->status_domisili = 'Tetap';
    }

    public function scanKtp()
    {
        $this->validate([
            'foto_ktp' => 'required|image|max:2048',
        ]);

        try {
            $scanner = app(KtpScannerService::class);
            $data = $scanner->scan($this->foto_ktp);

            if (! empty($data['nik'])) {
                $this->nik = $data['nik'];
            }
            if (! empty($data['name'])) {
                $this->name = $data['name'];
            }
            if (! empty($data['alamat'])) {
                $this->alamat = $data['alamat'];
            }
            if (! empty($data['jenis_kelamin'])) {
                $this->jenis_kelamin = $data['jenis_kelamin'];
            }
            if (! empty($data['tempat_lahir'])) {
                $this->tempat_lahir = $data['tempat_lahir'];
            }
            if (! empty($data['tanggal_lahir'])) {
                $this->tanggal_lahir = $data['tanggal_lahir'];
            }

            $filled = array_filter([
                $data['nik'], $data['name'], $data['alamat'],
                $data['jenis_kelamin'], $data['tempat_lahir'], $data['tanggal_lahir'],
            ], fn($v) => ! empty($v));

            if (count($filled) === 0) {
                $this->dispatch('notify', message: 'Tidak dapat membaca data KTP. Mohon foto ulang dengan pencahayaan lebih terang.', type: 'warning');
            } else {
                $this->dispatch('notify', message: 'Data KTP berhasil terbaca. Silakan periksa dan lengkapi.', type: 'success');
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', message: 'Gagal scan KTP: ' . $e->getMessage(), type: 'danger');
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16',
            'no_kk' => 'nullable|string|max:16',
            'alamat' => 'nullable|string|max:500',
            'status_domisili' => 'required|in:Tetap,Kontrak',
            'jenis_kelamin' => 'nullable|in:LAKI-LAKI,PEREMPUAN',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'foto_wajah' => 'nullable|image|max:2048',
            'foto_ktp' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $this->foto_wajah ? $this->foto_wajah->store('warga/foto', 'public') : null;
        $ktpPath = $this->foto_ktp ? $this->foto_ktp->store('warga/ktp', 'public') : null;

        if ($this->editMode) {
            $warga = Warga::where('rt_id', auth()->user()->rt_id)->where('id', $this->editId)->firstOrFail();
            
            $updateData = [
                'nik' => $this->nik,
                'no_kk' => $this->no_kk,
                'name' => $this->name,
                'alamat' => $this->alamat,
                'status_domisili' => $this->status_domisili,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
            ];

            if ($fotoPath) $updateData['foto_path'] = $fotoPath;
            if ($ktpPath) $updateData['ktp_path'] = $ktpPath;

            $warga->update($updateData);
        } else {
            Warga::create([
                'rt_id' => auth()->user()->rt_id,
                'nik' => $this->nik,
                'no_kk' => $this->no_kk,
                'name' => $this->name,
                'alamat' => $this->alamat,
                'status_domisili' => $this->status_domisili,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'foto_path' => $fotoPath,
                'ktp_path' => $ktpPath,
            ]);
        }
        
        $this->resetForm();
    }

    public function render()
    {
        $wargas = auth()->user()->rt->wargas()
            ->when($this->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('nik', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterDomisili, fn($q) =>
                $q->where('status_domisili', $this->filterDomisili)
            )
            ->paginate($this->perPage);

        return view('livewire.warga-index', compact('wargas'))
            ->layout('layouts.app');
    }
}
