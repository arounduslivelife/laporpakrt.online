<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Warga;
use App\Services\KtpScannerService;
use App\Traits\DetectsMobile;

class WargaIndex extends Component
{
    use WithPagination;
    use WithFileUploads;
    use DetectsMobile;

    public $nik, $no_kk, $name, $alamat, $status_domisili = 'Tetap', $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $status_perkawinan, $status_keluarga, $penghasilan, $is_disabilitas = false;
    public $foto_wajah, $foto_ktp;
    public $foto_path, $ktp_path;
    public $selectedBantuans = [];
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
        $warga = Warga::with('bantuans')->where('rt_id', auth()->user()->rt_id)->findOrFail($id);
        $this->fill($warga->only(['nik', 'no_kk', 'name', 'alamat', 'status_domisili', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'foto_path', 'ktp_path', 'status_perkawinan', 'status_keluarga', 'penghasilan', 'is_disabilitas']));
        $this->selectedBantuans = $warga->bantuans->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->editMode = true;
        $this->editId = $id;
        $this->showForm = true;
    }

    public $viewWarga = null;

    public function view($id)
    {
        $this->viewWarga = Warga::with('bantuans')->where('rt_id', auth()->user()->rt_id)->findOrFail($id);
    }

    public function closeView()
    {
        $this->viewWarga = null;
    }

    public function delete($id)
    {
        Warga::where('rt_id', auth()->user()->rt_id)->findOrFail($id)->delete();
    }

    public function resetForm()
    {
        $this->reset(['nik', 'no_kk', 'name', 'alamat', 'status_domisili', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_perkawinan', 'status_keluarga', 'penghasilan', 'is_disabilitas', 'editMode', 'editId', 'showForm', 'foto_wajah', 'foto_ktp', 'foto_path', 'ktp_path', 'forceSave', 'showDuplicateWarning', 'duplicateInfo', 'selectedBantuans']);
        $this->status_domisili = 'Tetap';
    }

    public function scanKtp()
    {
        $this->validate([
            'foto_ktp' => 'required|image|max:8192',
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

    public $forceSave = false;
    public $showDuplicateWarning = false;
    public $duplicateInfo = null;

    public function confirmSave()
    {
        $this->forceSave = true;
        $this->save();
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
            'foto_wajah' => 'nullable|image|max:8192',
            'foto_ktp' => 'nullable|image|max:8192',
            'status_perkawinan' => 'nullable|in:Belum Kawin,Kawin Tercatat,Kawin Belum Tercatat,Cerai Hidup,Cerai Mati',
            'status_keluarga' => 'nullable|in:Kepala Keluarga,Suami,Isteri,Anak,Menantu,Cucu,Orang Tua,Mertua,Famili Lain,Pembantu,Lainnya',
            'penghasilan' => 'nullable|string',
            'is_disabilitas' => 'boolean',
        ]);

        if (!empty($this->nik) && !$this->forceSave) {
            $query = Warga::where('nik', $this->nik);
            if ($this->editMode) {
                $query->where('id', '!=', $this->editId);
            }
            $duplicate = $query->with('rt')->first();

            if ($duplicate) {
                if ($duplicate->rt_id == auth()->user()->rt_id) {
                    $this->addError('nik', 'NIK ini sudah terdaftar di RT Anda.');
                    return;
                } else {
                    $this->showDuplicateWarning = true;
                    $this->duplicateInfo = [
                        'name' => $duplicate->name,
                        'rt' => $duplicate->rt ? "RT " . $duplicate->rt->rt . " / RW " . $duplicate->rt->rw : 'Tidak diketahui'
                    ];
                    return;
                }
            }
        }

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
                'status_perkawinan' => $this->status_perkawinan,
                'status_keluarga' => $this->status_keluarga,
                'penghasilan' => $this->penghasilan,
                'is_disabilitas' => $this->is_disabilitas,
            ];

            if ($fotoPath) $updateData['foto_path'] = $fotoPath;
            if ($ktpPath) $updateData['ktp_path'] = $ktpPath;

            $warga->update($updateData);
            $warga->bantuans()->sync($this->selectedBantuans);
        } else {
            $newWarga = Warga::create([
                'rt_id' => auth()->user()->rt_id,
                'nik' => $this->nik,
                'no_kk' => $this->no_kk,
                'name' => $this->name,
                'alamat' => $this->alamat,
                'status_domisili' => $this->status_domisili,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'status_perkawinan' => $this->status_perkawinan,
                'status_keluarga' => $this->status_keluarga,
                'penghasilan' => $this->penghasilan,
                'is_disabilitas' => $this->is_disabilitas,
                'foto_path' => $fotoPath,
                'ktp_path' => $ktpPath,
            ]);
            $newWarga->bantuans()->sync($this->selectedBantuans);
        }
        
        $this->resetForm();
    }

    public function render()
    {
        $matched = auth()->user()->rt->wargas()
            ->when($this->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('nik', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filterDomisili, fn($q) =>
                $q->where('status_domisili', $this->filterDomisili)
            )
            ->get();

        $kkGroups = $matched->map(function($w) {
            return !empty($w->no_kk) ? $w->no_kk : 'tanpa_kk_' . $w->id;
        })->unique()->values();

        $page = $this->getPage();
        $perPage = $this->perPage;
        
        $paginatedKks = new \Illuminate\Pagination\LengthAwarePaginator(
            $kkGroups->forPage($page, $perPage),
            $kkGroups->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $allWargas = auth()->user()->rt->wargas()->orderBy('id')->get();
        
        $groupedWargas = [];
        foreach ($paginatedKks as $kk) {
            if (str_starts_with($kk, 'tanpa_kk_')) {
                $id = str_replace('tanpa_kk_', '', $kk);
                $groupedWargas[$kk] = $allWargas->where('id', $id);
            } else {
                $groupedWargas[$kk] = $allWargas->where('no_kk', $kk);
            }
        }

        $bantuansList = \App\Models\Bantuan::all();

        if ($this->isMobile()) {
            return view('livewire.mobile.warga-index', compact('groupedWargas', 'paginatedKks', 'bantuansList'))
                ->layout('layouts.mobile', ['title' => 'Warga']);
        }

        return view('livewire.warga-index', compact('groupedWargas', 'paginatedKks', 'bantuansList'))
            ->layout('layouts.app');
    }
}
