<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Exports\WargaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class WargaExportController extends Controller
{
    public function excel()
    {
        return Excel::download(new WargaExport, 'Data_Warga_RT_' . auth()->user()->rt->rt . '.xlsx');
    }

    public function pdf()
    {
        $wargas = Warga::where('rt_id', auth()->user()->rt_id)->get();
        $pdf = Pdf::loadView('exports.warga-pdf', compact('wargas'));
        return $pdf->download('Data_Warga_RT_' . auth()->user()->rt->rt . '.pdf');
    }
}
