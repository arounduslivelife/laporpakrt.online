<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Exports\WargaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class WargaExportController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new WargaExport($request), 'Data_Warga_RT_' . auth()->user()->rt->rt . '.xlsx');
    }

    public function pdf(Request $request)
    {
        $wargas = (new WargaExport($request))->query()->get();
        $pdf = Pdf::loadView('exports.warga-pdf', compact('wargas'));
        return $pdf->download('Data_Warga_RT_' . auth()->user()->rt->rt . '.pdf');
    }
}
