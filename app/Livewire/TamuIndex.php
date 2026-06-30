<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Tamu;
use App\Services\KtpScannerService;

class TamuIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $name, $nik, $alamat, $tujuan, $lama_kunjungan_hari = 1;
    public $lat, $lng;
    public $foto_wajah, $foto_ktp; // String paths from DB
    public $file_wajah, $file_ktp; // Uploaded file instances
    public $showForm = false;
    public $editMode = false;
    public $editId = null;

    public function mount()
    {
        abort_if(!in_array(auth()->user()->role, ['rt_admin', 'security']), 403);
    }

    public $search = '';
    public $perPage = 10;

    public function updatingSearch() { $this->resetPage(); }

    public function edit($id)
    {
        $tamu = Tamu::where('rt_id', auth()->user()->rt_id)->findOrFail($id);
        $this->fill($tamu->only(['name', 'nik', 'alamat', 'tujuan', 'lama_kunjungan_hari', 'lat', 'lng', 'foto_wajah', 'foto_ktp']));
        $this->editMode = true;
        $this->editId = $id;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Tamu::where('rt_id', auth()->user()->rt_id)->findOrFail($id)->delete();
    }

    public function resetForm()
    {
        $this->reset(['name', 'nik', 'alamat', 'tujuan', 'lama_kunjungan_hari', 'lat', 'lng', 'editMode', 'editId', 'showForm', 'foto_wajah', 'foto_ktp', 'file_wajah', 'file_ktp']);
        $this->lama_kunjungan_hari = 1;
    }

    public function scanKtp()
    {
        $this->validate([
            'file_ktp' => 'required|image|max:2048',
        ]);

        try {
            $scanner = app(KtpScannerService::class);
            $data = $scanner->scan($this->file_ktp);

            if (! empty($data['nik'])) {
                $this->nik = $data['nik'];
            }
            if (! empty($data['name'])) {
                $this->name = $data['name'];
            }
            if (! empty($data['alamat'])) {
                $this->alamat = $data['alamat'];
            }

            $filled = array_filter([$data['nik'], $data['name'], $data['alamat']], fn($v) => ! empty($v));

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
            'alamat' => 'nullable|string|max:500',
            'tujuan' => 'required|string|max:255',
            'lama_kunjungan_hari' => 'required|integer|min:1',
            'file_wajah' => 'nullable|image|max:2048',
            'file_ktp' => 'nullable|image|max:2048',
        ]);

        $wajahPath = $this->file_wajah ? $this->file_wajah->store('tamu/wajah', 'public') : null;
        $ktpPath = $this->file_ktp ? $this->file_ktp->store('tamu/ktp', 'public') : null;

        if ($this->editMode) {
            $tamu = Tamu::where('rt_id', auth()->user()->rt_id)->where('id', $this->editId)->firstOrFail();
            
            $updateData = [
                'name' => $this->name,
                'nik' => $this->nik,
                'alamat' => $this->alamat,
                'tujuan' => $this->tujuan,
                'lama_kunjungan_hari' => $this->lama_kunjungan_hari,
                'lat' => $this->lat,
                'lng' => $this->lng,
            ];

            if ($wajahPath) $updateData['foto_wajah'] = $wajahPath;
            if ($ktpPath) $updateData['foto_ktp'] = $ktpPath;

            $tamu->update($updateData);
        } else {
            Tamu::create([
                'rt_id' => auth()->user()->rt_id,
                'name' => $this->name,
                'nik' => $this->nik,
                'alamat' => $this->alamat,
                'tujuan' => $this->tujuan,
                'lama_kunjungan_hari' => $this->lama_kunjungan_hari,
                'lat' => $this->lat,
                'lng' => $this->lng,
                'foto_wajah' => $wajahPath,
                'foto_ktp' => $ktpPath,
                'created_by' => auth()->id(),
            ]);
        }
        
        $this->resetForm();
    }

    public function render()
    {
        $tamus = auth()->user()->rt->tamus()
            ->when($this->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('tujuan', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.tamu-index', compact('tamus'))
            ->layout('layouts.app');
    }
}
