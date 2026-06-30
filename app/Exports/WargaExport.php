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

    public function query()
    {
        return Warga::where('rt_id', auth()->user()->rt_id);
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
