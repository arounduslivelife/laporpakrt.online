<?php

namespace App\Exports;

use App\Models\Warga;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WargaExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Warga::where('rt_id', auth()->user()->rt_id);

        if ($this->request) {
            $query->when($this->request->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$this->request->search}%")
                      ->orWhere('nik', 'like', "%{$this->request->search}%")
                )
            )
            ->when($this->request->domisili, fn($q) =>
                $q->where('status_domisili', $this->request->domisili)
            )
            ->when($this->request->penghasilan, fn($q) =>
                $q->where('penghasilan', $this->request->penghasilan)
            )
            ->when($this->request->perkawinan, fn($q) =>
                $q->where('status_perkawinan', $this->request->perkawinan)
            )
            ->when($this->request->disabilitas !== null && $this->request->disabilitas !== '', fn($q) =>
                $q->where('is_disabilitas', $this->request->disabilitas)
            )
            ->when($this->request->bantuan, fn($q) =>
                $q->whereHas('bantuans', fn($q2) => $q2->where('bantuan_id', $this->request->bantuan))
            );
        }

        return $query;
    }

    public function map($warga): array
    {
        return [
            $warga->nik,
            $warga->no_kk,
            $warga->name,
            $warga->status_domisili,
        ];
    }

    public function headings(): array
    {
        return [
            'NIK',
            'No KK',
            'Nama Lengkap',
            'Status Domisili',
        ];
    }
}
